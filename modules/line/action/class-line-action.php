<?php
/**
 * Classe gérant les actions des lignes de notes de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 * @subpackage LigneDeFrais
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les actions des lignes de note de frais.
 */
class Line_Action {

	/**
	 * Instanciate Note De Frais Line
	 */
	public function __construct() {
		add_action( 'wp_ajax_fp_create_line', array( $this, 'callback_fp_create_line' ) );
		add_action( 'wp_ajax_fp_update_line', array( $this, 'callback_fp_update_line' ) );

		add_action( 'wp_ajax_fp_delete_line', array( $this, 'callback_fp_delete_line' ) );
		add_action( 'wp_ajax_fp_dissociate_line_from_note', array( $this, 'callback_fp_dissociate_line_from_note' ) );

		add_action( 'wp_ajax_fp_create_line_from_picture', array( $this, 'callback_fp_create_line_from_picture' ) );
	}

	/**
	 * Create a new empty line in a note.
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_fp_create_line() {
		check_ajax_referer( 'fp_create_line' );

		$line_args              = array();
		$line_args['parent_id'] = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : -1;
		$line_args['status']    = 'inherit';

		$line = Line_Class::g()->create( $line_args );

		ob_start();
		Line_Class::g()->display( $line );
		$line_view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'fraisPro',
			'module'           => 'line',
			'callback_success' => 'displayLine',
			'line'             => $line,
			'view'             => $line_view,
		) );
	}

	/**
	 * Mark a line as trash in database in order to "delete" it.
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_fp_delete_line() {
		check_ajax_referer( 'fp_delete_line' );

		$line_id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : -1;

		// Translators: $1 connected user id. $2 the line to delete.
		\eoxia\LOG_Util::log( sprintf( __( 'User %1$d try to delete the line %2$d', 'frais-pro' ), get_current_user_id(), $line_id ), 'frais-pro' );

		if ( 0 >= $line_id ) {
			// Translators: $1 given id.
			\eoxia\LOG_Util::log( sprintf( __( 'The given ID %1$d is invalid', 'frais-pro' ), $line_id ), 'frais-pro' );
			wp_send_json_error( array( 'message' => __( 'You try to delete a line that does not exists', 'frais-pro' ) ) );
		}

		$line = Line_Class::g()->update( array(
			'id'     => $line_id,
			'status' => 'trash',
		) );

		wp_send_json_success( array(
			'namespace'        => 'fraisPro',
			'module'           => 'line',
			'callback_success' => 'deleteLineFromDisplay',
			'line'             => $line,
		) );
	}

	/**
	 * Dissociate the line from a note by removing parent identifier.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_fp_dissociate_line_from_note() {
		check_ajax_referer( 'fp_dissociate_line_from_note' );

		$line_id   = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : -1;
		$parent_id = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : -1;

		// Translators: $1 connected user id. $2 the line to dissociate.
		\eoxia\LOG_Util::log( sprintf( __( 'User %1$d try to dissociate the line %2$d from %3$d', 'digirisk' ), get_current_user_id(), $line_id, $parent_id ), 'frais-pro' );

		if ( 0 >= $line_id ) {
			// Translators: $1 given line id.
			\eoxia\LOG_Util::log( sprintf( __( 'The given line ID %1$d is invalid', 'digirisk' ), $line_id ), 'frais-pro' );
			wp_send_json_error( array( 'message' => __( 'You try to dissociate a line that does not exists', 'frais-pro' ) ) );
		}

		if ( 0 === $parent_id ) {
			// Translators: $1 given note id.
			\eoxia\LOG_Util::log( sprintf( __( 'The given note ID %1$d is invalid', 'digirisk' ), $parent_id ), 'frais-pro' );
			wp_send_json_error( array( 'message' => __( 'You try to dissociate a line from a note that does not exists', 'frais-pro' ) ) );
		}

		$note            = Note_Class::g()->get( array( 'id' => $parent_id ), true );
		$unaffected_note = Note_Class::g()->create_unaffected_note( $note->data['author_id'] );

		$line = Line_Class::g()->update( array(
			'id'        => $line_id,
			'parent_id' => $unaffected_note->data['id'],
		), true );

		$unaffected_note->data['count_line']++;
		Note_Class::g()->update( $unaffected_note->data );
		
		$compilated_tax_amount           = 0;
		$compilated_tax_inclusive_amount = 0;

		$lines = Line_Class::g()->get( array(
			'post_parent' => $note->data['id'],
		) );
		foreach ( $lines as $line ) {
			$compilated_tax_inclusive_amount += $line->data['tax_inclusive_amount'];
			$compilated_tax_amount           += $line->data['tax_amount'];
		}

		$note->data['date_modified']        = current_time( 'mysql' );
		$note->data['tax_inclusive_amount'] = $compilated_tax_inclusive_amount;
		$note->data['tax_amount']           = $compilated_tax_amount;
		Note_Class::g()->update( $note->data );

		wp_send_json_success( array(
			'namespace'            => 'fraisPro',
			'module'               => 'line',
			'callback_success'     => 'deleteLineFromDisplay',
			'line'                 => $line,
			'id'                   => $note->data['id'],
			'tax_inclusive_amount' => $note->data['tax_inclusive_amount'],
			'tax_amount'           => $note->data['tax_amount'],
			
		) );
	}

	/**
	 * Create a new line on a professionnal fees sheet for each sended picture.
	 *
	 * @since 1.2.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_fp_create_line_from_picture() {
		check_ajax_referer( 'fp_create_line_from_picture' );

		$files_id  = ! empty( $_POST['files_id'] ) ? (array) $_POST['files_id'] : array();
		$note_id   = ! empty( $_POST['note_id'] ) ? (integer) $_POST['note_id'] : array();
		$line_view = '';
		$lines     = null;

		if ( empty( $files_id ) || ! is_int( $note_id ) ) {
			wp_send_json_error( array( 'message' => __( 'There is a missing parameter', 'frais-pro' ) ) );
		}

		if ( ! empty( $files_id ) ) {
			foreach ( $files_id as $file_id ) {
				$line_args              = array();
				$line_args['parent_id'] = isset( $_POST['note_id'] ) ? intval( $_POST['note_id'] ) : -1;
				$line_args['status']    = 'inherit';

				$line    = Line_Class::g()->create( $line_args );
				$lines[] = $line;

				\eoxia\WPEO_Upload_Class::g()->set_thumbnail( array(
					'id'         => $line->data['id'],
					'file_id'    => (int) $file_id,
					'model_name' => '\frais_pro\Line_Class',
				) );

				ob_start();
				Line_Class::g()->display( $line );
				$line_view .= ob_get_clean();
			}
		}

		wp_send_json_success( array(
			'view'  => $line_view,
			'lines' => $lines,
		) );
	}

	/**
	 * Mise à jour d'une ligne
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_fp_update_line() {
		check_ajax_referer( 'fp_update_line' );

		// Définition des arguments pour la mise à jour de la ligne.
		$line_args                         = array();
		$line_args['id']                   = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$line_args['title']                = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : __( 'Label', 'frais-pro' );
		$line_args['date_modified']        = isset( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : current_time( 'mysql' );
		$line_args['parent_id']            = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : 0;
		$line_args['distance']             = isset( $_POST['distance'] ) ? intval( $_POST['distance'] ) : 0;
		$line_args['tax_amount']           = isset( $_POST['tax_amount'] ) ? floatval( str_replace( ',', '.', $_POST['tax_amount'] ) ) : 0;
		$line_args['tax_inclusive_amount'] = isset( $_POST['tax_inclusive_amount'] ) ? floatval( str_replace( ',', '.', $_POST['tax_inclusive_amount'] ) ) : 0;

		// Ajout de la catégorie de la ligne.
		$line_args['taxonomy'][ Line_Type_Class::g()->get_type() ][] = isset( $_POST['type'] ) ? intval( $_POST['type'] ) : 0;

		// Enregistrement de la ligne.
		$line = Line_Class::g()->update( $line_args, true );
		$note = Note_Class::g()->get( array( 'id' => $line_args['parent_id'] ), true );

		wp_send_json_success( array(
			'namespace'        => 'fraisPro',
			'module'           => 'line',
			'callback_success' => 'lineSaved',
			'note'             => $note,
			'note_last_update' => __( 'Last Update', 'frais-pro' ) . ' ' . $note->data['date_modified']['rendered']['date_human_readable'],
			'line'             => $line,
		) );
	}

}

new Line_Action();
