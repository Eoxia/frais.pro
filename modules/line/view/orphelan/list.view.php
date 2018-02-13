<?php
/**
 * Affichage du tableau ainsi que la ligne pour ajouter une ligne de note de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 * @subpackage LigneDeFrais
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?><div class="single-note <?php echo esc_attr( $display_mode ); ?>">
	<div class="container">
		<div class="header">
			<a href="<?php echo esc_url( menu_page_url( \eoxia\Config_Util::$init['frais-pro']->slug, false ) ); ?>" class="close"><i class="icon far fa-chevron-left"></i></a>
			<div class="title">
				<h2>
				<?php
					// Translators: %d the number of orphelan lines.
					echo esc_html( sprintf( __( 'Unaffected lines (%d)', 'frais-pro' ), count( $lines ) ) );
				?>
				</h2>
				<div class="note-last-update" ><?php esc_html_e( 'Last update', 'frais-pro' ); ?> : <?php echo esc_html( $last_line_date ); ?></div>
			</div>
		</div>

		<div class="content">
			<div class="note-action">
				<div class="display-method">
					<span class="wpeo-button button-square-50 button-grey <?php echo esc_attr( 'grid' === $display_mode ? 'active' : '' ); ?>" data-display-type="grid" ><i class="icon fas fa-th-large"></i></span>
					<span class="wpeo-button button-square-50 button-grey <?php echo esc_attr( 'list' === $display_mode ? 'active' : '' ); ?>" data-display-type="list" ><i class="icon far fa-list-ul"></i></span>
				</div> <!-- .display-method -->
			</div> <!-- .note-action -->

			<?php if ( ! empty( $lines ) ) : ?>
				<?php \eoxia\View_Util::exec( 'frais-pro', 'line', 'main', array( 'lines' => $lines ) ); ?>
			<?php endif; ?>
		</div> <!-- .content -->

	</div> <!-- .container -->
</div> <!-- .single-note -->
