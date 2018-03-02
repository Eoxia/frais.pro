<?php
/**
 * Handle search.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle search.
 */
class Search_Class extends \eoxia\Singleton_Util {

	/**
	 * Constructor for Singleton_Util
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	protected function construct() {}

	/**
	 * Display the search view.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function display() {
		\eoxia\View_Util::exec( 'frais-pro', 'search', 'main' );
	}

}

Search_Class::g();
