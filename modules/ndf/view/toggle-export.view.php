<?php
/**
 * Affichage du toggle pour gérer les types de note.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<span class="action" contenteditable="false">
	<i class="fa fa-ellipsis-v"></i>
</span>
<ul class="content" style="color: black; font-size: 0.5em;">
	<li class="action-attribute"
			data-id="<?php echo esc_attr( $ndf->id ); ?>"
			data-action="export_ndf"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_ndf' ) ); ?>"
			data-picture="1"><?php esc_html_e( 'With picture', 'frais-pro' ); ?></li>
	<li class="action-attribute"
			data-id="<?php echo esc_attr( $ndf->id ); ?>"
			data-action="export_ndf"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_ndf' ) ); ?>"
			data-picture="0"><?php esc_html_e( 'Without picture', 'frais-pro' ); ?></li>
	<li class="action-attribute"
			data-id="<?php echo esc_attr( $ndf->id ); ?>"
			data-action="export_csv"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_csv' ) ); ?>"><?php esc_html_e( 'CSV', 'frais-pro' ); ?></li>
</ul>
