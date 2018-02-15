<?php
/**
 * Affichage du toggle pour gérer les types de note.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2017-2018 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-dropdown dropdown-right" >
	<button class="dropdown-toggle wpeo-button button-main button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Generate file', 'frais-pro' ); ?>" ><i class="button-icon far fa-sync-alt"></i></button>
	<ul class="dropdown-content" >
		<li class="dropdown-item action-attribute"
			data-id="<?php echo esc_attr( $note->id ); ?>"
			data-action="fp_export_note"
			data-type="odf"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_export_note' ) ); ?>"
			data-picture="1"><i class="icon fa-fw fas fa-file-image"></i>&nbsp;<?php esc_html_e( 'With picture', 'frais-pro' ); ?></li>
		<li class="dropdown-item action-attribute"
			data-id="<?php echo esc_attr( $note->id ); ?>"
			data-action="fp_export_note"
			data-type="odf"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_export_note' ) ); ?>"
			data-picture="0"><i class="icon fa-fw fas fa-file"></i>&nbsp;<?php esc_html_e( 'Without picture', 'frais-pro' ); ?></li>
		<li class="dropdown-item action-attribute"
			data-id="<?php echo esc_attr( $note->id ); ?>"
			data-action="fp_export_note"
			data-type="csv"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_export_note' ) ); ?>"><i class="icon fa-fw fas fa-file-excel"></i>&nbsp;<?php esc_html_e( 'CSV export', 'frais-pro' ); ?></li>
	</ul>
</div>
<div class="wpeo-dropdown dropdown-right" >
	<button class="dropdown-toggle wpeo-button button-main button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Download file', 'frais-pro' ); ?>" ><i class="button-icon far fa-arrow-to-bottom" ></i></button>
	<ul class="dropdown-content" >
		<li class="dropdown-item action-attribute wpeo-tooltip-event"
			data-id="<?php echo esc_attr( $note->id ); ?>"
			data-action="download_export"
			aria-label="Fichier non généré"
			data-direction="left"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'download_export' ) ); ?>"
			data-picture="1"><i class="icon fa-fw fas fa-file-image"></i>&nbsp;<?php esc_html_e( 'With picture', 'frais-pro' ); ?></li>
		<li class="dropdown-item action-attribute"
			data-id="<?php echo esc_attr( $note->id ); ?>"
			data-action="download_export"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'download_export' ) ); ?>"
			data-picture="0"><i class="icon fa-fw fas fa-file"></i>&nbsp;<?php esc_html_e( 'Without picture', 'frais-pro' ); ?></li>
		<li class="dropdown-item action-attribute"
			data-id="<?php echo esc_attr( $note->id ); ?>"
			data-action="download_export"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'download_export' ) ); ?>"><i class="icon fa-fw fas fa-file-excel"></i>&nbsp;<?php esc_html_e( 'CSV export', 'frais-pro' ); ?></li>
	</ul>
</div>
<div class="wpeo-dropdown dropdown-right" >
	<button class="dropdown-toggle wpeo-button button-transparent wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Options', 'frais-pro' ); ?>" ><i class="button-icon far fa-ellipsis-v"></i></button>
	<ul class="dropdown-content" >
		<li class="dropdown-item action-attribute"
			data-id="<?php echo esc_attr( $note->id ); ?>"
			data-action="fp_note_archive"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_note_archive' ) ); ?>"><i class="icon fa-fw fas fa-archive"></i>&nbsp;<?php esc_html_e( 'Archive', 'frais-pro' ); ?></li>
	</ul>
</div>
