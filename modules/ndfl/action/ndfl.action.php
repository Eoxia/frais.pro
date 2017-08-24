<?php
/**
 * Classe gérant les actions des lignes de notes de frais.
 *
 * @author eoxia
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2017 Eoxia
 * @package ndfl
 * @subpackage action
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les actions des lignes de note de frais.
 */
class NDFL_Action {

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

		$ndf_id = isset( $_POST['ndf_id'] ) ? intval( $_POST['ndf_id'] ) : -1;
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
	 * @return [type] [description]
	 */
	public function callback_modify_ndfl() {
		check_ajax_referer( 'modify_ndfl' );

		$ndf_id = isset( $_POST['ndf_id'] ) ? intval( $_POST['ndf_id'] ) : -1;

		if ( isset( $_POST['row'] ) ) {
			foreach ( $_POST['row'] as $row ) {
				$row['post_parent'] = $ndf_id;
				$current_row = NDFL_Class::g()->update( $row );
				$all_new_row[] = $current_row->id;
			}
		}

		ob_start();
		NDFL_Class::g()->display( $ndf_id );
		$response = ob_get_clean();

		$ndf = NDF_Class::g()->get( array(
			'post__in' => array( $ndf_id ),
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
	 * [callback_delete_ndfl description]
	 * @return [type] [description]
	 */
	public function callback_delete_ndfl() {
		check_ajax_referer( 'delete_ndfl' );

		$ndf_id = isset( $_POST['ndf_id'] ) ? intval( $_POST['ndf_id'] ) : -1;
		$row_to_delete = isset( $_POST['ndfl_id'] ) ? intval( $_POST['ndfl_id'] ) : -1;

		$row = NDFL_Class::g()->get( array(
			'post__in' => array( $row_to_delete ),
		), true );

		$row->status = 'trash';
		NDFL_Class::g()->update( $row );

		ob_start();
		NDFL_Class::g()->display( $ndf_id );
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