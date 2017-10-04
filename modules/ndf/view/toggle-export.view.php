<?php
/**
 * Affichage du toggle pour gérer les types de note.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package NDF
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<span class="action" contenteditable="false">
	<i class="icon ion-ios-download-outline"></i>
</span>
<ul class="content" style="color: black; font-size: 0.5em;">
	<li class="action-attribute"
			data-id="<?php echo esc_attr( $ndf->id ); ?>"
			data-action="export_ndf"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_ndf' ) ); ?>"
			data-picture="1"><?php esc_html_e( 'With picture', 'note-de-frais' ); ?></li>
	<li class="action-attribute"
			data-id="<?php echo esc_attr( $ndf->id ); ?>"
			data-action="export_ndf"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_ndf' ) ); ?>"
			data-picture="0"><?php esc_html_e( 'Without picture', 'note-de-frais' ); ?></li>
	<li class="action-attribute"
			data-id="<?php echo esc_attr( $ndf->id ); ?>"
			data-action="export_csv"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_csv' ) ); ?>"><?php esc_html_e( 'CSV', 'note-de-frais' ); ?></li>
</ul>
