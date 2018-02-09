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

		add_action( 'wp_ajax_modify_ndfl', array( $this, 'callback_modify_ndfl' ) );
		add_action( 'wp_ajax_delete_ndfl', array( $this, 'callback_delete_ndfl' ) );

		add_action( 'wp_ajax_fp_create_line_from_picture', array( $this, 'callback_fp_create_line_from_picture' ) );
	}

	/**
	 * [callback_add_ndfl description]
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
	 * Create a new line on a professionnal fess sheet for each sended picture.
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

				// \eoxia\WPEO_Upload_Class::g()->set_thumbnail( array(
				// 	'id'         => $line->id,
				// 	'file_id'    => (int) $file_id,
				// 	'model_name' => '\frais_pro\Line_Class',
				// ) );

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

	/**
	 * Passes la ligne de frais en status 'trash'.
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 *
	 * @return void
	 */
	public function callback_delete_ndfl() {
		check_ajax_referer( 'delete_ndfl' );

		$note_id = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : -1;
		$row_to_delete = isset( $_POST['ndfl_id'] ) ? intval( $_POST['ndfl_id'] ) : -1;
		$display_mode = isset( $_POST['display_mode'] ) ? sanitize_text_field( $_POST['display_mode'] ) : 'list';

		$row = Line_Class::g()->get( array(
			'id' => $row_to_delete,
		), true );

		$row->status = 'trash';
		Line_Class::g()->update( $row );

		ob_start();
		Line_Class::g()->display( $row->parent_id, $display_mode );
		$response = ob_get_clean();

		wp_send_json_success( array(
			'namespace' => 'fraisPro',
			'module' => 'NDF',
			'callback_success' => 'refresh',
			'view' => $response,
		) );
	}

}

new Line_Action();
