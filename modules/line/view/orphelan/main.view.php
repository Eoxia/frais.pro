<?php
/**
 * Display orphelan lines.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017-2018 Eoxia
 * @package Eoxia/Frais.pro
 * @subpackage LigneDeFrais
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?><table class="wpeo-table list-note orphelan-lines main" >
	<tbody>
		<tr class="note" data-link="<?php menu_page_url( 'frais-pro', true ); ?>&note=unclassified">
			<td class="note-status" >&nbsp;</td>
			<td class="note-title">
				<span>
				<?php
					// Translators: %d the number of orphelan lines.
					echo esc_html( sprintf( __( 'Unaffected lines (%d)', 'frais-pro' ), count( $lines ) ) );
				?>
				</span>
				<div class="note-last-update" ><?php esc_html_e( 'Last update', 'frais-pro' ); ?> : <?php echo esc_html( $last_line_date ); ?></div>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="note-action">
				<span class="export toggle list" data-parent="toggle" data-target="content">
					<div class="wpeo-dropdown dropdown-right" >
						<button class="dropdown-toggle wpeo-button button-transparent wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Options', 'frais-pro' ); ?>" ><i class="button-icon far fa-ellipsis-v"></i></button>
						<ul class="dropdown-content" >
							<li class="dropdown-item action-attribute"
								data-action="fp_delete_orphelan_lines"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_delete_orphelan_lines' ) ); ?>"><i class="icon fas fa-archive"></i>&nbsp;<?php esc_html_e( 'Delete all lines', 'frais-pro' ); ?></li>
						</ul>
					</div>
				</span>
			</td>
		</tr>
	</tbody>
</table>
