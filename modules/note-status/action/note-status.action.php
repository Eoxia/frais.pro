<?php
/**
 * Classe gérant les actions des types de note des notes de frais.
 *
 * @author Eoxia
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package NDF
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les actions des types de note des notes de frais.
 */
class Note_Status_Action {

	/**
	 * Le constructeur
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'callback_admin_init' ) );
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 99 );
	}

	/**
	 * Permet d'intialiser les traductions des type de note au bon moment.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_admin_init() {
		Note_Status_Class::g()->init_status_note();
	}

	/**
	 * Ajoutes un sous menu "Categories" qui renvoie vers la page pour créer les catégories de NDF.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_admin_menu() {
		add_submenu_page( 'frais-pro', __( 'Note status', 'frais-pro' ), __( 'Note status', 'frais-pro' ), 'manage_options', 'edit-tags.php?taxonomy=' . Note_Status_Class::g()->get_type() );
	}

}

new Note_Status_Action();
