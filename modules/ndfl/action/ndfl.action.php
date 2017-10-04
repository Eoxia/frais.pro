<?php
/**
 * Classe gérant les actions des lignes de notes de frais.
 *
 * @author eoxia
 * @since 1.0.0
 * @version 1.2.0
 * @copyright 2017 Eoxia
 * @package NDF
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les actions des lignes de note de frais.
 */
class NDFL_Action {

	/**
	 * Instanciate Note De Frais Line
	 */
	public function __construct() {
		add_action( 'wp_ajax_add_ndfl', array( $this, 'callback_add_ndfl' ) );
		add_action( 'wp_ajax_modify_ndfl', array( $this, 'callback_modify_ndfl' ) );
		add_action( 'wp_ajax_delete_ndfl', array( $this, 'callback_delete_ndfl' ) );
	}

	/**
	 * [callback_add_ndfl description]
	 */
	public function callback_add_ndfl() {
		check_ajax_referer( 'add_ndfl' );

		$ndf_id = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : -1;
		NDFL_Class::g()->create( $_POST );

		ob_start();
		NDF_Class::g()->display( $ndf_id );
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

		$ndf_id = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : -1;
		$display_mode = isset( $_POST['display_mode'] ) ? sanitize_text_field( $_POST['display_mode'] ) : 'list';

		if ( isset( $_POST['row'] ) ) {
			foreach ( $_POST['row'] as $row ) {
				$row['parent_id'] = $ndf_id;
				$row['tax_inclusive_amount'] = str_replace( ',', '.', $row['tax_inclusive_amount'] );
				$row['tax_amount'] = str_replace( ',', '.', $row['tax_amount'] );
				$current_row = NDFL_Class::g()->update( $row );

				if ( ! empty( $row['thumbnail_id'] ) ) {
					\eoxia\WPEO_Upload_Class::g()->set_thumbnail( $current_row->id, $row['thumbnail_id'], '\note_de_frais\NDFL_Class' );
				}
			}
		}

		ob_start();
		NDFL_Class::g()->display( $ndf_id, $display_mode );
		$response = ob_get_clean();

		$ndf = NDF_Class::g()->get( array(
			'id' => $ndf_id,
		), true );

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'callback_success' => 'refresh',
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

		$row = NDFL_Class::g()->get( array(
			'id' => $row_to_delete,
		), true );

		$row->status = 'trash';
		NDFL_Class::g()->update( $row );

		ob_start();
		NDFL_Class::g()->display( $row->parent_id, $display_mode );
		$response = ob_get_clean();

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'callback_success' => 'refresh',
			'view' => $response,
		) );
	}

}

new NDFL_Action();
