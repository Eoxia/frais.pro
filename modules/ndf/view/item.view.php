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

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="note action-attribute"
		data-id="<?php echo esc_attr( $ndf->id ); ?>"
		data-action="open_ndf"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'open_ndf' ) ); ?>">
	<div class="container">
		<div class="header">
			<h2 class="title"><?php echo esc_html( $ndf->title ); ?></h2>
			<span class="button archive action-attribute tooltip hover"
						data-id="<?php echo esc_attr( $ndf->id ); ?>"
						data-action="archive_ndf"
						aria-label="<?php esc_html_e( 'Delete' ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'archive_ndf' ) ); ?>"><i class="icon ion-ios-trash-outline"></i></span>

			<span class="export toggle list" data-parent="toggle" data-target="content">
				<?php \eoxia\View_Util::exec( 'frais-pro', 'ndf', 'toggle-export', array(
					'ndf' => $ndf,
				) ); ?>
			</span>
		</div>
		<div class="content gridwrapper">
			<div class="ttc element">
				<span class="value"><?php echo esc_html( $ndf->tax_inclusive_amount ); ?></span>
				<span class="currency"><?php esc_html_e( '€', 'frais-pro' ); ?></span>
				<span class="taxe"><?php esc_html_e( 'ATI', 'frais-pro' ); ?></span>
			</div>
			<div class="tva element">
				<span class="value"><?php echo esc_html( $ndf->tax_amount ); ?></span>
				<span class="currency"><?php esc_html_e( '€', 'frais-pro' ); ?></span>
				<span class="taxe"><?php esc_html_e( 'Recoverable VAT', 'frais-pro' ); ?></span>
			</div>
			<div class="status"><span class="value pin-status <?php echo ! empty( $ndf->validation_status ) ? esc_attr( NDF_Class::g()->get_status( $ndf->validation_status ) ) : ''; ?>"><?php echo esc_html( $ndf->validation_status ); ?></span></div>
			<div class="update"><?php esc_html_e( 'Last update', 'frais-pro' ); ?> : <span class="value"><?php echo esc_html( $ndf->date_modified['date_human_readable'] ); ?></span></div>
		</div>
	</div>
</div>
