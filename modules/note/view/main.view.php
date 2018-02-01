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
	<h1>
		<?php esc_html_e( 'Professionnal fees sheets', 'frais-pro' ); ?>

		<div class="wpeo-button button-blue action-attribute button-size-small button-radius-2"
				data-action="create_ndf"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_ndf' ) ); ?>" >
			<i class="button-icon fas fa-plus-circle"></i>
			<span><?php esc_html_e( 'Add' ); ?></span>
		</div>
	</h1>

<?php if ( ! empty( $ndfs ) ) : ?>
	<table class="wpeo-table list-note main" >
		<tbody>
			<?php
			foreach ( $ndfs as $ndf ) :
				\eoxia\View_Util::exec( 'frais-pro', 'note', 'item', array( 'ndf' => $ndf ) );
			endforeach;
			?>
		</tbody>
	</table>
<?php else : ?>
	<div class="notice notice-info" >
		<p><?php esc_html_e( 'Actually you do not have professionnal fees sheet', 'frais-pro' ); ?></p>
	</div>
<?php endif; ?>
