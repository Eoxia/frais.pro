<?php
/**
 * Vue principale de l'application
 *
 * @package Eoxia\Plugin
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<li>
	<p><?php echo esc_html( $ndf->title ); ?></p>
	<a class="action-attribute"
			data-id="<?php echo esc_attr( $ndf->id ); ?>"
			data-action="export_note_de_frais"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_note_de_frais' ) ); ?>">Exporter</a>
	<p><?php echo esc_html( $ndf->ttc ); ?></p>
	<p><?php echo esc_html( $ndf->tx_tva ); ?></p>
</li>
