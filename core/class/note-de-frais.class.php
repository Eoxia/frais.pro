<?php
/**
 * Classe gérant le boot de l'application Frais.pro
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2015-2017 Eoxia
 * @package Frais.pro
 * @subpackage Core_Class
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {	exit; }

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
			'waiting_updates' => get_option( '_fp_waited_updates', array() ),
		) );
	}

}

new Note_De_Frais_Class();
