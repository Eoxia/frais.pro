<?php
/**
 * Vue principale de l'application
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

Search_Class::g()->display();

Note_Class::g()->display_list( array(
	'meta_query' => array(
		'relation' => 'OR',
		array(
			'key'     => 'fp_contains_unaffected',
			'value'   => false,
			'compare' => '=',
		),
		array(
			'key'     => 'fp_contains_unaffected',
			'compare' => 'NOT EXISTS',
		),
	),
));

Note_Class::g()->display_list( array(
	'display_only_has_note' => true,
	'custom_css'            => array(
		'list-note-unaffected',
	),
	'meta_query'            => array(
		array(
			'key'     => 'fp_contains_unaffected',
			'value'   => true,
			'compare' => '=',
		),
	),
) );
