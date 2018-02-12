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
		\eoxia\View_Util::exec( 'frais-pro', 'core', 'main', array(
			'waiting_updates' => get_option( \eoxia\Config_Util::$init['frais-pro']->key_waited_updates, array() ),
			'user'            => User_Class::g()->get( array( 'id' => get_current_user_id() ), true ),
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

			// Define current version for the Frais.pro plugin.
			$version = (int) str_replace( '.', '', \eoxia\Config_Util::$init['frais-pro']->version );
			if ( 3 === strlen( $version ) ) {
				$version *= 10;
			}
			update_option( \eoxia\Config_Util::$init['frais-pro']->key_last_update_version, $version );
		}
	}

}

new Note_De_Frais_Class();
