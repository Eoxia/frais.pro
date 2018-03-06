<?php
/**
 * Manage filters for line.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017-2018 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage filters for line.
 */
class Document_Filter {

	/**
	 * Instanciate filters for frais.pro.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function __construct() {
		$current_type = Document_Class::g()->get_type();
		add_filter( "eo_model_{$current_type}_before_post", '\frais_pro\before_post_identifier', 10, 2 );
		add_filter( "eo_model_{$current_type}_after_get", '\frais_pro\after_get_identifier', 10, 2 );
	}

}

new Document_Filter();
