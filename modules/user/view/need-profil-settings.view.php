<?php
/**
 * Display modal box indicating to the user he must set is profil before using application.
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
} ?>
<?php
echo wp_kses(
	sprintf( __( 'Your %1$sprice per kilometer%2$s is not setted. Please go to %3$syour profile%4$s in order to set it.', 'frais-pro' ), '<strong>', '</strong>', '<a target="_blank" href="' . esc_url( get_edit_profile_url() ) . '">', '</a>' ),
	array(
		'strong' => array(),
		'a'      => array(
			'target' => array(),
			'href'   => array(),
		),
	)
);
