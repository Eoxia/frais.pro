<?php
/**
 * Ajoutes le nombre de ligne dans le titre.
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
} ?>

&nbsp;<span class="count-line">(<?php echo esc_html( $note->data['count_line'] ); ?>)</span>
