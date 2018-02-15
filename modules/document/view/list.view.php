<?php
/**
 * LIste des documents pour une note
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
} ?><div class="document-list-container" >
	<h3><?php esc_html_e( 'Export history', 'frais-pro' ); ?></h3>
	<table class="wpeo-table list-document main">
		<tbody>
		<?php
		if ( ! empty( $documents ) ) :
			foreach ( $documents as $document ) :
				\eoxia\View_Util::exec( 'frais-pro', 'document', 'item', array(
					'document' => $document,
				) );
			endforeach;
		else :
			?>
			<tr class="notice notice-info">
				<td colspan="3" ><?php esc_html_e( 'Actually you do not have any file generated', 'frais-pro' ); ?></td>
			</tr>
		<?php
		endif;
		?>
		</tbody>
	</table>
</div>
