<?php
/**
 * Ajoutes des informations supplémentaire sur la ligne d'une note.
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

<td class="note-ttc">
	<span class="value"><?php echo esc_html( $note['tax_inclusive_amount'] ); ?></span>
	<span class="currency"><?php esc_html_e( '€', 'frais-pro' ); ?></span>
	<span class="taxe"><?php esc_html_e( 'ATI', 'frais-pro' ); ?></span>
</td>

<td class="note-tva">
	<span class="value"><?php echo esc_html( $note['tax_amount'] ); ?></span>
	<span class="currency"><?php esc_html_e( '€', 'frais-pro' ); ?></span>
	<span class="taxe"><?php esc_html_e( 'VAT', 'frais-pro' ); ?></span>
</td>
