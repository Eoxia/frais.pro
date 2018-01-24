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
<tr class="note" data-id="<?php echo esc_attr( $ndf->id ); ?>" >
	<td class="note-status" ><?php echo esc_html( $ndf->validation_status ); ?></td>
	<td>
		<a href="<?php menu_page_url( 'frais-pro', true ); ?>&note=<?php echo esc_attr( $ndf->id ); ?>" ><?php echo esc_html( $ndf->title ); ?></a>
		<div class="note-last-update" ><?php esc_html_e( 'Last update', 'frais-pro' ); ?> : <?php echo esc_html( $ndf->date_modified['date_human_readable'] ); ?></div>
	</td>
	<td>
		<span class="value"><?php echo esc_html( $ndf->tax_inclusive_amount ); ?></span>
		<span class="currency"><?php esc_html_e( '€', 'frais-pro' ); ?></span>
		<span class="taxe"><?php esc_html_e( 'ATI', 'frais-pro' ); ?></span>
	</td>
	<td>
		<span class="value"><?php echo esc_html( $ndf->tax_amount ); ?></span>
		<span class="currency"><?php esc_html_e( '€', 'frais-pro' ); ?></span>
		<span class="taxe"><?php esc_html_e( 'VAT', 'frais-pro' ); ?></span>
	</td>
	<td></td>
	<td>
		<span class="export toggle list" data-parent="toggle" data-target="content">
			<?php \eoxia\View_Util::exec( 'frais-pro', 'note', 'actions', array(
				'ndf' => $ndf,
			) ); ?>
		</span>
	</td>
</tr>
