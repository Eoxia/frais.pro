<?php
/**
 * Classe gérant les mises à jour.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les mises à jour de Task Manager.
 */
class Update_Manager extends \eoxia\Singleton_Util {

	/**
	 * Constructeur obligatoire pour Singleton_Util
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	protected function construct() {}

	/**
	 * Récupères les mises à jour en attente et appel la vue "main" du module "update_manager".
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function display() {
		$waiting_updates = get_option( \eoxia\Config_Util::$init['frais-pro']->key_waited_updates, array() );
		\eoxia\View_Util::exec( 'frais-pro', 'update_manager', 'main', array(
			'waiting_updates' => $waiting_updates,
		) );
	}

}

new Update_Manager();
