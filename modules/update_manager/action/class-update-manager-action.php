<?php
/**
 * Gestion des actions pour les mises à jours.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe de gestion des "actions" pour le module de mise à jour des données suite aux différentes version de l'extension
 */
class Update_Manager_Action extends \eoxia\Update_Manager_Action {

	/**
	 * Instanciation de la classe de gestions des mises à jour des données suite aux différentes versions de l'extension
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 12 );
		add_action( 'wp_loaded', array( $this, 'automatic_update_redirect' ) );
		add_action( 'wp_ajax_tm_redirect_to_dashboard', array( $this, 'callback_tm_redirect_to_dashboard' ) );
	}

}

new Update_Manager_Action();
