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

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="wpeo-dropdown dropdown-left" >
	<button class="dropdown-toggle wpeo-button button-main button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Generate file', 'frais-pro' ); ?>" ><i class="button-icon fa fa-refresh"></i></button>
	<ul class="dropdown-content" >
		<li class="dropdown-item"
				data-id="<?php echo esc_attr( $ndf->id ); ?>"
				data-action="archive_ndf"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'archive_ndf' ) ); ?>"><i class="icon fa fa-archive"></i>&nbsp;<?php esc_html_e( 'Archive', 'frais-pro' ); ?></li>
	</ul>
</div>
<div class="wpeo-dropdown dropdown-left" >
	<button class="dropdown-toggle wpeo-button button-main button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Download file', 'frais-pro' ); ?>" ><i class="button-icon fa fa-download" ></i></button>
	<ul class="dropdown-content" >
		<li class="dropdown-item"
				data-id="<?php echo esc_attr( $ndf->id ); ?>"
				data-action="archive_ndf"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'archive_ndf' ) ); ?>"><i class="icon fa fa-archive"></i>&nbsp;<?php esc_html_e( 'Archive', 'frais-pro' ); ?></li>
	</ul>
</div>
<div class="wpeo-dropdown dropdown-left" >
	<button class="dropdown-toggle wpeo-button button-transparent wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Options', 'frais-pro' ); ?>" ><i class="button-icon fa fa-ellipsis-v"></i></button>
	<ul class="dropdown-content" >
		<li class="dropdown-item"
				data-id="<?php echo esc_attr( $ndf->id ); ?>"
				data-action="archive_ndf"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'archive_ndf' ) ); ?>"><i class="icon fa fa-archive"></i>&nbsp;<?php esc_html_e( 'Archive', 'frais-pro' ); ?></li>
	</ul>
</div>
