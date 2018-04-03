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

<div class="wpeo-dropdown dropdown-right fp-note-export-dropdown" >
	<button class="dropdown-toggle wpeo-button button-main button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Generate file', 'frais-pro' ); ?>" ><i class="button-icon far fa-arrow-to-bottom"></i></button>
	<ul class="dropdown-content" >
		<li class="dropdown-item clear" >
				<p class="alignleft" ><i class="icon fa-fw fas fa-file-image">&nbsp;</i><?php esc_html_e( 'With picture', 'frais-pro' ); ?></p>
				<a class="wpeo-button button-main button-square-40 button-rounded wpeo-tooltip-event <?php echo $note->data['last_document']['note-photo']['file_informations']['exists'] ? '' : esc_attr( 'button-disable' ); ?> alignright"
						aria-label="<?php echo esc_attr( $note->data['last_document']['note-photo']['tooltip'] ); ?>"
						data-direction="left"
						href="<?php echo esc_attr( $note->data['last_document']['note-photo']['file_informations']['link'] ); ?>">
					<i class="button-icon far fa-arrow-to-bottom">&nbsp;</i>
				</a>
				<button class="wpeo-button button-main button-square-40 button-rounded wpeo-tooltip-event alignright action-attribute"
					data-id="<?php echo esc_attr( $note->data['id'] ); ?>"
					data-action="export_note"
					data-extension="odt"
					data-category="note-photo"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_note' ) ); ?>"
					data-picture="1" aria-label="<?php esc_html_e( 'Generate file', 'frais-pro' ); ?>" ><i class="icon fa-fw fas fa-sync-alt"></i></button>
		</li>
		<li class="dropdown-item clear">
				<p class="alignleft" ><i class="icon fa-fw fas fa-file">&nbsp;</i><?php esc_html_e( 'Without picture', 'frais-pro' ); ?></p>
				<a class="wpeo-button button-main button-square-40 button-rounded wpeo-tooltip-event <?php echo $note->data['last_document']['note']['file_informations']['exists'] ? '' : esc_attr( 'button-disable' ); ?> alignright"
						aria-label="<?php echo esc_attr( $note->data['last_document']['note']['tooltip'] ); ?>"
						data-direction="left"
						href="<?php echo esc_attr( $note->data['last_document']['note']['file_informations']['link'] ); ?>">
					<i class="button-icon far fa-arrow-to-bottom">&nbsp;</i>
				</a>
				<button class="wpeo-button button-main button-square-40 button-rounded wpeo-tooltip-event alignright action-attribute"
					data-id="<?php echo esc_attr( $note->data['id'] ); ?>"
					data-action="export_note"
					data-extension="odt"
					data-category="note"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_note' ) ); ?>"
					data-picture="0" aria-label="<?php esc_html_e( 'Generate file', 'frais-pro' ); ?>" ><i class="icon fa-fw fas fa-sync-alt"></i></button>
		</li>
		<li class="dropdown-item clear">
				<p class="alignleft" ><i class="icon fa-fw fas fa-file-excel">&nbsp;</i><?php esc_html_e( 'CSV export', 'frais-pro' ); ?></p>
				<a class="wpeo-button button-main button-square-40 button-rounded wpeo-tooltip-event <?php echo $note->data['last_document']['note-csv']['file_informations']['exists'] ? '' : esc_attr( 'button-disable' ); ?> alignright"
						aria-label="<?php echo esc_attr( $note->data['last_document']['note-csv']['tooltip'] ); ?>"
						data-direction="left"
						href="<?php echo esc_attr( $note->data['last_document']['note-csv']['file_informations']['link'] ); ?>">
					<i class="button-icon far fa-arrow-to-bottom">&nbsp;</i>
				</a>
				<button class="wpeo-button button-main button-square-40 button-rounded wpeo-tooltip-event alignright action-attribute"
					data-id="<?php echo esc_attr( $note->data['id'] ); ?>"
					data-action="export_note"
					data-extension="csv"
					data-category="note-csv"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_note' ) ); ?>" aria-label="<?php esc_html_e( 'Generate file', 'frais-pro' ); ?>" ><i class="icon fa-fw fas fa-sync-alt"></i></button>
		</li>
	</ul>
</div>
<div class="wpeo-dropdown dropdown-right" >
<?php if ( 'archive' !== $note->data['status'] ) : ?>
	<button class="dropdown-toggle wpeo-button button-transparent wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Options', 'frais-pro' ); ?>" ><i class="button-icon far fa-ellipsis-v"></i></button>
	<ul class="dropdown-content" >
		<li class="dropdown-item action-attribute"
			data-id="<?php echo esc_attr( $note->data['id'] ); ?>"
			data-action="fp_note_archive"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_note_archive' ) ); ?>"><i class="icon fa-fw fas fa-archive"></i>&nbsp;<?php esc_html_e( 'Archive', 'frais-pro' ); ?></li>
	</ul>
<?php else : ?>
	<button class="dropdown-toggle wpeo-button button-transparent">&nbsp;</button>
<?php endif; ?>
</div>
