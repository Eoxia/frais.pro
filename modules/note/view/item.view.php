<?php
/**
 * Vue principale de l'application
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
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
		<span><?php echo esc_html( apply_filters( 'fp_filter_note_item_title', $note->title, $note ) ); ?></span>
		<div class="note-last-update" ><?php esc_html_e( 'Last update', 'frais-pro' ); ?> : <?php echo esc_html( $note->date_modified['rendered']['date_human_readable'] ); ?></div>
	</td>

	<?php echo apply_filters( 'fp_filter_note_item_informations', $note, 'table' ); ?>

	<td class="note-action">
		<?php echo apply_filters( 'fp_filter_note_item_actions', $note ); ?>
	</td>
</tr>
