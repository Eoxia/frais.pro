<?php
/**
 * Classe gérant les actions des notes de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les notes de frais.
 */
class NDF_Action {

	/**
	 * Le constructeur
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_open_ndf', array( $this, 'callback_open_ndf' ) );
		add_action( 'wp_ajax_create_ndf', array( $this, 'callback_create_ndf' ) );
		add_action( 'wp_ajax_modify_ndf', array( $this, 'callback_modify_ndf' ) );
		add_action( 'wp_ajax_archive_ndf', array( $this, 'callback_archive_ndf' ) );
		add_action( 'wp_ajax_export_ndf', array( $this, 'callback_export_ndf' ) );
		add_action( 'wp_ajax_export_csv', array( $this, 'callback_export_ndf_to_csv' ) );

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Action : Ouvrir une note de frais.
	 */
	public function callback_open_ndf() {
		check_ajax_referer( 'open_ndf' );

		$ndf_id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : -1;
		$ndf_display_mode = isset( $_POST['display_mode'] ) ? sanitize_text_field( $_POST['display_mode'] ) : 'grid';

		ob_start();
		NDFL_Class::g()->display( $ndf_id, $ndf_display_mode );
		$response = ob_get_clean();

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'callback_success' => 'open',
			'view' => $response,
			'display_mode' => $ndf_display_mode,
		) );
	}

	/**
	 * Action : créer une note de frais.
	 */
	public function callback_create_ndf() {
		check_ajax_referer( 'create_ndf' );

		$ndf = NDF_Class::g()->update( array(
			'post_status' => 'publish',
		) );

		ob_start();
		NDFL_Class::g()->display( $ndf->id );
		$response = ob_get_clean();

		ob_start();
		NDF_Class::g()->display();
		$response_main_view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'noteDeFrais',
			'module'           => 'NDF',
			'callback_success' => 'open',
			'view'             => $response,
			'main_view'        => $response_main_view,
		) );
	}

	/**
	 * Action : modifier une note de frais.
	 *
	 * @since 1.0.0
	 * @version 1.3.0
	 */
	public function callback_modify_ndf() {
		check_ajax_referer( 'modify_ndf' );

		$display_mode = isset( $_POST['display_mode'] ) ? sanitize_text_field( $_POST['display_mode'] ) : 'list';

		$ndf = NDF_Class::g()->update( $_POST );

		ob_start();
		NDFL_Class::g()->display( $ndf->id, $display_mode );
		$response = ob_get_clean();

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'callback_success' => 'refresh',
			'ndf' => $ndf,
			'view' => $response,
		) );
	}

	/**
	 * Action : archiver une note de frais.
	 */
	public function callback_archive_ndf() {
		check_ajax_referer( 'archive_ndf' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$ndf = NDF_Class::g()->get( array(
			'post__in' => array( $id ),
		), true );

		$ndf->status = 'archive';

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'callback_success' => 'archived',
			'ndf' => NDF_Class::g()->update( $ndf ),
		) );
	}

	/**
	 * Génère un document .odt avec les données qui vont bien.
	 *
	 * @since 1.0.0
	 * @version 1.3.0
	 */
	public function callback_export_ndf() {
		check_ajax_referer( 'export_ndf' );

		$ndf_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$picture = ! empty( $_POST['picture'] ) ? (bool) $_POST['picture'] : false;

		if ( empty( $ndf_id ) ) {
			wp_send_json_error();
		}

		$response = NDF_Class::g()->generate_document( $ndf_id, $picture, 'odt' );

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'link' => $response['link'],
			'filename' => $response['filename'],
			'callback_success' => 'exportedNoteDeFraisSuccess',
		) );
	}

	/**
	 * Generate a csv file with the NDF content
	 */
	public function callback_export_ndf_to_csv() {
		check_ajax_referer( 'export_csv' );

		$ndf_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $ndf_id ) ) {
			wp_send_json_error();
		}

		$response = NDF_Class::g()->generate_document( $ndf_id, false, 'csv' );

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'link' => $response['link'],
			'filename' => $response['filename'],
			'callback_success' => 'exportedNoteDeFraisSuccess',
		) );
	}

	/**
	 * Register specific routes for NDF
	 */
	public function register_routes() {
		register_rest_route( __NAMESPACE__ . '/v' . \eoxia\Config_Util::$init['eo-framework']->wpeo_model->api_version , '/' . NDF_Class::g()->get_rest_base() . '/(?P<id>[\d]+)/details', array(
			array(
				'method' => \WP_REST_Server::READABLE,
				'callback'	=> function( $request ) {
					$full_note = NDF_Class::g()->get( array( 'id' => $request['id'] ), true );

					$full_note->children = NDFL_Class::g()->get( array( 'post_parent' => $request['id'] ) );

					return $full_note;
				},
				'permission_callback' => function() {
					if ( ( ! in_array( $_SERVER['REMOTE_ADDR'], \eoxia\Config_Util::$init['wpeo_model']->allowed_ip_for_unauthentified_access_rest, true ) ) && ! NDF_Class::g()->check_cap( 'get' ) ) {
						return false;
					}
					return true;
				},
			),
		), true );
	}

}

new NDF_Action();
