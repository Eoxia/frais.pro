<?php
/**
 * Affichage du toggle pour gérer les types de note.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017-2018 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-dropdown dropdown-right" >
	<button class="dropdown-toggle wpeo-button button-transparent wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Options', 'frais-pro' ); ?>" ><i class="button-icon far fa-ellipsis-v"></i></button>
	<ul class="dropdown-content" >
		<li class="dropdown-item action-attribute"
			data-id="<?php echo esc_attr( $note['id'] ); ?>"
			data-action="fp_delete_all_lines"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_delete_all_lines' ) ); ?>"><i class="icon fa-fw fas fa-archive"></i>&nbsp;<?php esc_html_e( 'Delete all lines', 'frais-pro' ); ?></li>
	</ul>
</div>
