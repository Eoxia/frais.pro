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
		add_action( 'ajax_add_ndf', array( $this, 'callback_____' ));
	}

	public function callback() {
		// check_axj

		// $_POST

		NDF_Class::g()->update( $_POST );

		wp_send_json_success( array(
			// 'namespace' => ''
		) );
	}
}

new NDF_Action();
