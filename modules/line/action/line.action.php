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

		add_action( 'wp_ajax_fraispro_create_line_from_picture', array( $this, 'ajaxcallback_fraispro_create_line_from_picture' ) );
	}

	/**
	 * [callback_add_ndfl description]
	 */
	public function callback_fp_create_line() {
		check_ajax_referer( 'fp_create_line' );

		$ndf_id = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : -1;
		Line_Class::g()->create( $_POST );

		ob_start();
		Note_Class::g()->display( $ndf_id );
		$response = ob_get_clean();

		wp_send_json_success( array(
			'response' => $response,
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
		$ndf_id = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : -1;
		$display_mode = isset( $_POST['display_mode'] ) ? sanitize_text_field( $_POST['display_mode'] ) : 'list';

		if ( isset( $_POST['row'] ) ) {
			foreach ( $_POST['row'] as $row ) {
				if ( isset( $row['id'] ) && ! empty( $row['id'] ) ) {
					$edit_mode = true;
				}
				$row['parent_id'] = $ndf_id;
				$current_row = Line_Class::g()->update( $row );
			}
		}

		ob_start();
		Line_Class::g()->display( $ndf_id, $display_mode );
		$response = ob_get_clean();

		$ndf = Note_Class::g()->get( array(
			'id' => $ndf_id,
		), true );

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
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

		$ndf_id = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : -1;
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
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'callback_success' => 'refresh',
			'view' => $response,
		) );
	}

	/**
	 * Pour chaque ID de fichier reçu, créer un EPI.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @return void
	 */
	public function ajaxcallback_fraispro_create_line_from_picture() {
		check_ajax_referer( 'fraispro_create_line_from_picture' );

		$files_id = ! empty( $_POST['files_id'] ) ? (array) $_POST['files_id'] : array();
		$ndf_id = ! empty( $_POST['ndf_id'] ) ? (integer) $_POST['ndf_id'] : array();

		if ( empty( $files_id ) || ! is_int( $ndf_id ) ) {
			wp_send_json_error();
		}

		if ( ! empty( $files_id ) ) {
			foreach ( $files_id as $file_id ) {
				$ndfl = Line_Class::g()->update( array( 'parent_id' => $ndf_id ) );

				\eoxia\WPEO_Upload_Class::g()->set_thumbnail( array(
					'id' => $ndfl->id,
					'file_id' => $file_id,
					'model_name' => '\frais_pro\Line_Class',
				) );
			}
		}

		ob_start();
		Line_Class::g()->display( $ndf_id, 'grid' );
		$response = ob_get_clean();

		wp_send_json_success( array(
			'view' => $response,
		) );
	}

}

new Line_Action();
