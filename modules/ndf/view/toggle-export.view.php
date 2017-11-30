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
			data-picture="1"><i class="icon fa fa-download"></i>&nbsp;<?php esc_html_e( 'With picture', 'frais-pro' ); ?></li>
	<li class="action-attribute"
			data-id="<?php echo esc_attr( $ndf->id ); ?>"
			data-action="export_ndf"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_ndf' ) ); ?>"
			data-picture="0"><i class="icon fa fa-download"></i>&nbsp;<?php esc_html_e( 'Without picture', 'frais-pro' ); ?></li>
	<li class="action-attribute"
			data-id="<?php echo esc_attr( $ndf->id ); ?>"
			data-action="export_csv"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_csv' ) ); ?>"><i class="icon fa fa-table"></i>&nbsp;<?php esc_html_e( 'CSV export', 'frais-pro' ); ?></li>

	<li class="action-attribute"
			data-id="<?php echo esc_attr( $ndf->id ); ?>"
			data-action="archive_ndf"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'archive_ndf' ) ); ?>"><i class="icon fa fa-archive"></i>&nbsp;<?php esc_html_e( 'Archive', 'frais-pro' ); ?></li>
</ul>
