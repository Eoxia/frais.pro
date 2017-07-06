<?php
/**
 * Classe gérant les actions NDF
 *
 * @author eoxia
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2017 Eoxia
 * @package ndf
 * @subpackage action
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Classe gérant les actions NDF
 */
class NDF_Action {

	public function __construct() {
		add_action( 'wp_ajax_open_note_de_frais', array( $this, 'callback_open_note_de_frais' ) );
		add_action( 'wp_ajax_modify_note_de_frais', array( $this, 'callback_modify_note_de_frais' ) );
		add_action( 'wp_ajax_delete_note_de_frais', array( $this, 'callback_delete_note_de_frais' ) );
		add_action( 'wp_ajax_add_note_de_frais', array( $this, 'callback_add_note_de_frais' ) );
	}

	public function callback_open_note_de_frais() {
		check_ajax_referer( 'open_note_de_frais' );

		$group_id = isset( $_POST['group_id'] ) ? intval( $_POST['group_id'] ) : -1;

		ob_start();
		NDF_Class::g()->display( $group_id );
		$response = ob_get_clean();

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'callback_success' => 'openNdf',
			'view' => $response,
		) );
	}

	public function callback_modify_note_de_frais() {
		check_ajax_referer( 'modify_note_de_frais' );

		$group_id = isset( $_POST['group_id'] ) ? intval( $_POST['group_id'] ) : -1;

		if ( isset( $_POST['row'] ) ) {
			foreach ( $_POST['row'] as $row ) {
				$row['post_parent'] = $group_id;
				$current_row = NDF_Class::g()->update( $row );
				$all_new_row[] = $current_row->id;
			}
		}

		ob_start();
		NDF_Class::g()->display( $group_id );
		$response = ob_get_clean();

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'callback_success' => 'refreshNDF',
			'view' => $response,
		) );
	}

	public function callback_delete_note_de_frais() {
		check_ajax_referer( 'delete_note_de_frais' );

		$group_id = isset( $_POST['group_id'] ) ? intval( $_POST['group_id'] ) : -1;
		$row_to_delete = isset( $_POST['ndf_id'] ) ? intval( $_POST['ndf_id'] ) : -1;

		wp_delete_post( $row_to_delete, true );

		ob_start();
		NDF_Class::g()->display( $group_id );
		$response = ob_get_clean();

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'callback_success' => 'refreshNDF',
			'view' => $response,
		) );
	}

	public function callback_add_note_de_frais() {
		check_ajax_referer( 'add_note_de_frais' );

		$group_id = isset( $_POST['group_id'] ) ? intval( $_POST['group_id'] ) : -1;
		NDF_Class::g()->create( $_POST );

		wp_send_json_success( array(
			'namespace' => '',
			'module' => '',
			'callback_success' => '',
			'response' => NDF_Class::g()->display( $group_id ),
		) );
	}
}

new NDF_Action();
