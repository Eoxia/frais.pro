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

		add_action( 'wp_ajax_fp_delete_line', array( $this, 'callback_fp_delete_line' ) );
		add_action( 'wp_ajax_fp_dissociate_line_from_note', array( $this, 'callback_fp_dissociate_line_from_note' ) );

		add_action( 'wp_ajax_fp_create_line_from_picture', array( $this, 'callback_fp_create_line_from_picture' ) );

		add_action( 'wp_ajax_fp_delete_orphelan_lines', array( $this, 'callback_fp_delete_orphelan_lines' ) );

		add_action( 'wp_ajax_modify_ndfl', array( $this, 'callback_modify_ndfl' ) );
	}

	/**
	 * Create a new empty line in a note.
	 */
	public function callback_fp_create_line() {
		check_ajax_referer( 'fp_create_line' );

		$line_args              = array();
		$line_args['parent_id'] = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : -1;

		$line = Line_Class::g()->create( $line_args );
		ob_start();
		Line_Class::g()->display( $line );
		$line_view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'fraisPro',
			'module'           => 'Line',
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
		\eoxia\LOG_Util::log( sprintf( __( 'User %1$d try to delete the line %2$d', 'digirisk' ), get_current_user_id(), $line_id ), 'frais-pro' );

		if ( 0 >= $line_id ) {
			// Translators: $1 given id.
			\eoxia\LOG_Util::log( sprintf( __( 'The given ID %1$d is invalid', 'digirisk' ), $line_id ), 'frais-pro' );
			wp_send_json_error( array( 'message' => __( 'You try to delete a line that does not exists', 'frais-pro' ) ) );
		}

		$line = Line_Class::g()->update( array(
			'id'     => $line_id,
			'status' => 'trash',
		), true );

		wp_send_json_success( array(
			'namespace'        => 'fraisPro',
			'module'           => 'Line',
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

		$line_id        = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : -1;
		$parent_line_id = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : -1;

		// Translators: $1 connected user id. $2 the line to dissociate.
		\eoxia\LOG_Util::log( sprintf( __( 'User %1$d try to dissociate the line %2$d from %3$d', 'digirisk' ), get_current_user_id(), $line_id, $parent_line_id ), 'frais-pro' );

		if ( 0 >= $line_id ) {
			// Translators: $1 given line id.
			\eoxia\LOG_Util::log( sprintf( __( 'The given line ID %1$d is invalid', 'digirisk' ), $line_id ), 'frais-pro' );
			wp_send_json_error( array( 'message' => __( 'You try to dissociate a line that does not exists', 'frais-pro' ) ) );
		}

		if ( 0 >= $parent_line_id ) {
			// Translators: $1 given note id.
			\eoxia\LOG_Util::log( sprintf( __( 'The given note ID %1$d is invalid', 'digirisk' ), $parent_line_id ), 'frais-pro' );
			wp_send_json_error( array( 'message' => __( 'You try to dissociate a line from a note that does not exists', 'frais-pro' ) ) );
		}

		$line = Line_Class::g()->update( array(
			'id'        => $line_id,
			'parent_id' => 0,
		), true );

		wp_send_json_success( array(
			'namespace'        => 'fraisPro',
			'module'           => 'Line',
			'callback_success' => 'deleteLineFromDisplay',
			'line'             => $line,
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

		if ( empty( $files_id ) || ! is_int( $note_id ) ) {
			wp_send_json_error( array( 'message' => __( 'There is a missing parameter', 'frais-pro' ) ) );
		}

		if ( ! empty( $files_id ) ) {
			foreach ( $files_id as $file_id ) {
				$line = Line_Class::g()->update( array( 'parent_id' => $note_id ) );

				\eoxia\WPEO_Upload_Class::g()->set_thumbnail( array(
					'id'         => $line->id,
					'file_id'    => (int) $file_id,
					'model_name' => '\frais_pro\Line_Class',
				) );

				ob_start();
				Line_Class::g()->display( $line );
				$line_view .= ob_get_clean();
			}
		}

		wp_send_json_success( array(
			'view' => $line_view,
		) );
	}

	/**
	 * Delete all lines that do not have a parent id in database.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_fp_delete_orphelan_lines() {
		check_ajax_referer( 'fp_delete_orphelan_lines' );

		// Mark all unaffected lines as trashed. Can not use wpeo_model in this case because of a mass action on update.
		$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts,
			array(
				'post_status' => 'trash',
			),
			array(
				'parent_id' => 0,
				'post_type' => Line_Class::g()->get_type(),
			)
		);
	}

	/**
	 * [callback_modify_ndfl description]
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 *
	 * @return void
	 */
	public function callback_modify_ndfl() {
		check_ajax_referer( 'modify_ndfl' );

		$edit_mode = false;
		$note_id = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : -1;
		$display_mode = isset( $_POST['display_mode'] ) ? sanitize_text_field( $_POST['display_mode'] ) : 'list';

		if ( isset( $_POST['row'] ) ) {
			foreach ( $_POST['row'] as $row ) {
				if ( isset( $row['id'] ) && ! empty( $row['id'] ) ) {
					$edit_mode = true;
				}
				$row['parent_id'] = $note_id;
				$current_row = Line_Class::g()->update( $row );
			}
		}

		ob_start();
		Line_Class::g()->display( $note_id, $display_mode );
		$response = ob_get_clean();

		$ndf = Note_Class::g()->get( array(
			'id' => $note_id,
		), true );

		wp_send_json_success( array(
			'namespace' => 'fraisPro',
			'module' => 'NDF',
			'callback_success' => 'refresh',
			'no_refresh' => $edit_mode,
			'ndf' => $ndf,
			'view' => $response,
		) );
	}

}

new Line_Action();
