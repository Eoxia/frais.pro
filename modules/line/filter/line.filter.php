<?php
/**
 * Manage filters for line.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017-2018 Eoxia
 * @package Eoxia/NodeDeFrais
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage filters for line.
 */
class Line_Filter {

	/**
	 * Instanciate filters for frais.pro.
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 */
	public function __construct() {
		add_filter( 'fp_filter_line_item_before', array( $this, 'callback_fp_filter_line_item_before' ), 10, 2 );
		add_filter( 'fp_filter_line_item_action_before', array( $this, 'callback_fp_filter_line_item_action_before' ), 10, 2 );
	}

	/**
	 * Filter callback allowing to display some content at the start of a line in a note.
	 *
	 * @param string     $content Current content before calling this filter.
	 * @param Line_Model $line    Current line definition.
	 *
	 * @return void
	 */
	public function callback_fp_filter_line_item_before( $content, $line ) {
		if ( 0 >= $line->parent_id ) {
			\eoxia\View_Util::exec( 'frais-pro', 'line', 'orphelan/checkbox', array(
				'line' => $line,
			) );
			$content = ob_get_clean();
		}

		echo $content; // WPCS: XSS ok.
	}

	/**
	 * Filter callback allowing to display some content at the top of actions on a line
	 *
	 * @param string     $content Current content before calling this filter.
	 * @param Line_Model $line    Current line definition.
	 *
	 * @return void
	 */
	public function callback_fp_filter_line_item_action_before( $content, $line ) {
		if ( 0 < $line->parent_id ) {
			\eoxia\View_Util::exec( 'frais-pro', 'line', 'dissociation', array(
				'line' => $line,
			) );
			$content = ob_get_clean();
		}

		echo $content; // WPCS: XSS ok.
	}

}

new Line_Filter();
