<?php
/**
 * Gestion des actions pour les mises à jours.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
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
class Update_Manager_Action {

	/**
	 * Define the current plugin slug to use for getting config var.
	 *
	 * @var string
	 */
	protected $current_module = 'frais-pro';

	/**
	 * Instanciation de la classe de gestions des mises à jour des données suite aux différentes versions de l'extension
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function __construct() {
		// When WordPress is loaded check if there are updates to do from defined files.
		add_action( 'wp_loaded', array( $this, 'automatic_update_redirect' ) );

		// Add the hidden update menu to back admin.
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 12 );

		// Appel de l'action permettant de rediriger l'utilisateur vers l'application principale une fois la mise à jour terminée.
		add_action( 'wp_ajax_tm_redirect_to_dashboard', array( $this, 'callback_redirect_to_main_application' ) );
	}

	/**
	 * On récupère la version actuelle de l'extension principale pour savoir si une mise à jour est nécessaire
	 * On regarde également si des mises à jour n'ont pas été faite suite à un suivi des mises à jours non régulier
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function automatic_update_redirect() {
		$waiting_updates = get_option( \eoxia\Config_Util::$init[ $this->current_module ]->key_waited_updates, array() );

		if ( ! strpos( $_SERVER['REQUEST_URI'], 'admin-ajax.php' ) ) {
			$current_version_to_check = (int) str_replace( '.', '', \eoxia\Config_Util::$init[ $this->current_module ]->version );
			$last_version_done        = (int) get_option( \eoxia\Config_Util::$init[ $this->current_module ]->key_last_update_version, 1300 );

			if ( 3 === strlen( $current_version_to_check ) ) {
				$current_version_to_check *= 10;
			}

			if ( $last_version_done !== $current_version_to_check ) {
				$update_path      = \eoxia\Config_Util::$init[ $this->current_module ]->update_manager->path . 'update/';
				$update_data_path = \eoxia\Config_Util::$init[ $this->current_module ]->update_manager->path . 'data/';

				for ( $i = ( (int) substr( $last_version_done, 0, 4 ) + 1 ); $i <= $current_version_to_check; $i++ ) {
					if ( is_file( $update_data_path . 'update-' . $i . '-data.php' ) ) {
						require_once $update_data_path . 'update-' . $i . '-data.php';
						$waiting_updates[ $i ] = $datas;

						update_option( \eoxia\Config_Util::$init[ $this->current_module ]->key_waited_updates, $waiting_updates );
					}
				}
			}
		}
	}

	/**
	 * Ajoutes une page invisible qui vas permettre la gestion des mises à jour.
	 *
	 * @return void
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function callback_admin_menu() {
		$menu_labels = sprintf( __( '%s Update.', 'frais-pro' ), \eoxia\Config_Util::$init[ $this->current_module ]->name );
		add_submenu_page( '123', $menu_labels, $menu_labels, 'manage_options', \eoxia\Config_Util::$init[ $this->current_module ]->update_page_url, array( Update_Manager::g(), 'display' ) );
	}

	/**
	 * AJAX Callback - Return the website url
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function callback_redirect_to_main_application() {
		\eoxia\LOG_Util::log( __( 'Data update done. User can go to main application', 'frais-pro' ), $this->current_module );

		$version = (int) str_replace( '.', '', \eoxia\Config_Util::$init[ $this->current_module ]->version );
		if ( 3 === strlen( $version ) ) {
			$version *= 10;
		}
		update_option( \eoxia\Config_Util::$init[ $this->current_module ]->key_last_update_version, $version );
		delete_option( \eoxia\Config_Util::$init[ $this->current_module ]->key_waited_updates );

		$data = array(
			'url'     => admin_url( 'admin.php?page=' . \eoxia\Config_Util::$init[ $this->current_module ]->slug ),
			'message' => __( 'Redirect to Frais.pro main application', 'frais-pro' ),
		);

		wp_send_json_success( $data );
	}

}

new Update_Manager_Action();
