<?php
/**
 * Formulaire pour éditer une ligne de note de frais en mode 'grille'.
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
} ?>
<div class="table-row line">
	<div class="table-cell line-image">
		<?php do_shortcode( '[wpeo_upload id="' . $ndfl->id . '" model_name="/frais_pro/Line_Class" single="true" size="full" ]' ); ?>
	</div>
	<div class="table-cell line-content wpeo-form">
		<div class="date form-element">
			<label><?php esc_attr_e( 'Date', 'frais-pro' ); ?></label>
			<input id="line-date" type="text" />
		</div>
		<div class="libelle form-element">
			<label><?php esc_attr_e( 'Libellé', 'frais-pro' ); ?></label>
			<input type="text" placeholder="<?php esc_attr_e( 'My entry', 'frais-pro' ); ?>" value="<?php echo esc_html( $ndfl->title ); ?>" />
		</div>
		<div class="type form-element">
			<label><?php esc_attr_e( 'Type de note', 'frais-pro' ); ?></label>
			<?php Type_Note_Class::g()->display( $ndfl ); ?>
		</div>
		<div class="km form-element">
			<label><?php esc_attr_e( 'Km', 'frais-pro' ); ?></label>
			<input type="text" value="<?php echo esc_html( $ndfl->distance ); ?>" />
		</div>
		<div class="ttc form-element">
			<label><?php esc_attr_e( 'TTC(€)', 'frais-pro' ); ?></label>
			<input type="text" value="<?php echo esc_html( $ndfl->tax_inclusive_amount ); ?>" />
		</div>
		<div class="tva form-element">
			<label><?php esc_attr_e( 'TVA récup.', 'frais-pro' ); ?></label>
			<input type="text" value="<?php echo esc_html( $ndfl->tax_amount ); ?>" />
		</div>
		<div class="status wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Ligne valide', 'frais-pro' ); ?>">
			<span class="pin <?php echo esc_attr( ! empty( $line_status ) && ( false === $line_status['status'] ) ? 'ndfl-error' : 'ndfl-ok' ); ?>"></span>
		</div>
		<div class="action">
			<div class="wpeo-dropdown dropdown-right">
				<button class="dropdown-toggle wpeo-button button-transparent wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Options', 'frais-pro' ); ?>"><i class="button-icon far fa-ellipsis-v"></i></button>
					<ul class="dropdown-content">
					<li class="dropdown-item"><i class="dropdown-icon far fa-unlink fa-fw"></i> <?php esc_html_e( 'Dissocier de la note', 'frais-pro' ); ?></li>
					<li class="dropdown-item"><i class="dropdown-icon fas fa-trash-alt fa-fw"></i> <?php esc_html_e( 'Supprimer', 'frais-pro' ); ?></li>
				</ul>
			</div>
		</div>
	</div> <!-- .line-content -->
</div>
