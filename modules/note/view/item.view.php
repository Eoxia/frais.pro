<?php
/**
 * Vue principale de l'application
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
<tr class="note" style="border-left-color:<?php echo esc_html( $note->fp_note_status->color ); ?>" data-link="<?php echo esc_url( admin_url( 'admin.php?page=frais-pro-edit' ) ); ?>&note=<?php echo esc_attr( $note->id ); ?>" data-id="<?php echo esc_attr( $note->id ); ?>" >
	<td class="note-status" >
		<span class="status-label"><?php echo esc_html( $note->fp_note_status->name ); ?></span>
	</td>
	<td class="note-title">
		<span><?php echo esc_html( $note->title ); ?></span>
		<div class="note-last-update" ><?php esc_html_e( 'Last update', 'frais-pro' ); ?> : <?php echo esc_html( $note->date_modified['rendered']['date_human_readable'] ); ?></div>
	</td>
	<td class="note-ttc">
		<span class="value"><?php echo esc_html( $note->tax_inclusive_amount ); ?></span>
		<span class="currency"><?php esc_html_e( '€', 'frais-pro' ); ?></span>
		<span class="taxe"><?php esc_html_e( 'ATI', 'frais-pro' ); ?></span>
	</td>
	<td class="note-tva">
		<span class="value"><?php echo esc_html( $note->tax_amount ); ?></span>
		<span class="currency"><?php esc_html_e( '€', 'frais-pro' ); ?></span>
		<span class="taxe"><?php esc_html_e( 'VAT', 'frais-pro' ); ?></span>
	</td>
	<td class="note-action">
		<span class="export toggle list" data-parent="toggle" data-target="content">
			<?php \eoxia\View_Util::exec( 'frais-pro', 'note', 'actions', array(
				'note' => $note,
			) ); ?>
		</span>
	</td>
</tr>
