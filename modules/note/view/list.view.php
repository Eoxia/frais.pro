<?php
/**
 * Vue principale de l'application
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<table class="wpeo-table list-note main <?php echo esc_attr( $custom_class ); ?>">
	<tbody>
	<?php
	if ( ! empty( $note_list ) ) :
		foreach ( $note_list as $note ) :
			\eoxia\View_Util::exec( 'frais-pro', 'note', 'item', array(
				'note' => $note->data,
			) );
		endforeach;
	else :
		?>
		<tr class="notice notice-info">
			<td colspan="5"><?php esc_html_e( 'Actually you do not have professionnal fees sheet', 'frais-pro' ); ?></td>
		</tr>
		<?php
	endif;
	?>
	</tbody>
</table>
