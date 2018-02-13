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
	 * Display the view search.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function display() {
		$status_list = Note_Status_Class::g()->get();

		\eoxia\View_Util::exec( 'frais-pro', 'note', 'search/main', array(
			'status_list' => $status_list,
		) );
	}

}

Search_Class::g();
