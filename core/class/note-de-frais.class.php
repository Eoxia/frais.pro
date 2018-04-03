<?php
/**
 * Classe gérant le boot de l'application Frais.pro
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2015-2018 Eoxia
 * @package Frais.pro
 * @subpackage Core_Class
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant le boot de l'application Frais.pro
 */
class Note_De_Frais_Class extends \eoxia\Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	protected function construct() {}

	/**
	 * La méthode qui permet d'afficher la page
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 */
	public function display() {
		$current_screen = get_current_screen();
		$view           = 'main';

		if ( \eoxia\Config_Util::$init['frais-pro']->menu_edit_parent_slug === $current_screen->parent_base ) {
			$view = 'main-single';
		}

		$user = User_Class::g()->get( array( 'id' => get_current_user_id() ), true );

		\eoxia\View_Util::exec( 'frais-pro', 'core', $view, array(
			'waiting_updates' => get_option( \eoxia\Config_Util::$init['frais-pro']->key_waiting_updates, array() ),
			'user'            => $user->data,
		) );
	}

	/**
	 * When plugin is activated on a website, get current version and set into database in order to avoid un-required updates.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 */
	public function init_default_data() {
		$current_version = get_option( \eoxia\Config_Util::$init['frais-pro']->key_last_update_version, null );
		if ( null === $current_version ) {
			// Call default note types creation.
			Line_Type_Class::g()->create_default_types();

			// Call default note status creation.
			Note_Status_Class::g()->create_default_statuses();

			$ndf_core = get_option( '_ndf_core', '' );

			if ( empty( $ndf_core ) ) {
				// Define current version for the Frais.pro plugin.
				$version = (int) str_replace( '.', '', \eoxia\Config_Util::$init['frais-pro']->version );
				if ( 3 === strlen( $version ) ) {
					$version *= 10;
				}
				update_option( \eoxia\Config_Util::$init['frais-pro']->key_last_update_version, $version );
			}
		}
	}

}

new Note_De_Frais_Class();
