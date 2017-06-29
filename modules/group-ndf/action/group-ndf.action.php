<?php
/**
 * Classe gérant les actions des groupes NDF
 *
 * @author eoxia
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2017 Eoxia
 * @package group-ndf
 * @subpackage action
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Classe gérant les groupe NDF
 */
class Group_NDF_Action {

	/**
	 * Le constructeur
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_export_note_de_frais', array( $this, 'callback_export_note_de_frais' ) );
	}

	/**
	 * Génère un document .odt avec les données qui vont bien
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_export_note_de_frais() {
		check_ajax_referer( 'callback_export_note_de_frais' );

		wp_send_json_success( array(
			'namespace' => '',
			'module' => '',
			'callback_success' => '',
		) );
	}
}

new Group_NDF_Action();
