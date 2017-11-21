<?php
/**
 * Formulaire pour éditer une ligne de note de frais en mode 'liste'.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 * @subpackage LigneDeFrais
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul class="row" data-i="<?php echo esc_attr( $i ); ?>"<?php echo esc_attr( ! empty( $ndfl ) && ! empty( $ndfl->current_category ) && ! empty( $ndfl->current_category->special_treatment ) ? ' special_treatment=' . $ndfl->current_category->special_treatment : '' ); ?>>
	<input type="hidden" name="id" value="<?php echo esc_attr( $ndfl->id ); ?>">
	<li class="group-date date" data-title="<?php esc_attr_e( 'Date', 'frais-pro' ); ?>" data-namespace="noteDeFrais" data-module="NDFL" data-after-method="changeDate" >
		<input type="text" class="mysql-date" style="width: 0px; padding: 0px; border: none; display: block; height: 0px;" name="date" value="<?php echo esc_attr( $ndfl->date['date_input']['date'] ); ?>" />
		<span contenteditable="<?php echo esc_attr( $ndf_is_closed ? 'false' : 'true' ); ?>" class="date"><?php echo esc_html( $ndfl->date['date_input']['fr_FR']['date'] ); ?></span>
	</li>
	<li class="libelle<?php echo esc_attr( ! empty( $line_status ) && ( false === $line_status['status'] ) && in_array( 'title', $line_status['errors'], true ) ? ' ndfl-error' : '' ); ?>" data-title="<?php esc_attr_e( 'Name', 'frais-pro' ); ?>"><span contenteditable="<?php echo esc_attr( $ndf_is_closed ? 'false' : 'true' ); ?>" data-name="row[<?php echo esc_attr( $i ); ?>][title]"><?php echo esc_html( $ndfl->title ); ?></span></li>
	<li class="type toggle list<?php echo esc_attr( ! empty( $line_status ) && ( false === $line_status['status'] ) && in_array( 'category_name', $line_status['errors'], true ) ? ' ndfl-error' : '' ); ?>" data-parent="toggle" data-target="content" data-title="<?php esc_attr_e( 'Line type', 'frais-pro' ); ?>">
		<?php Type_Note_Class::g()->display( $ndfl ); ?>
	</li>
	<li class="km" data-title="<?php esc_attr_e( 'Km', 'frais-pro' ); ?>"><span contenteditable="<?php echo esc_attr( $ndf_is_closed ? 'false' : 'true' ); ?>" data-name="row[<?php echo esc_attr( $i ); ?>][distance]" ><?php echo esc_html( $ndfl->distance ); ?></span></li>
	<li class="ttc<?php echo esc_attr( ! empty( $line_status ) && ( false === $line_status['status'] ) && in_array( 'tax_inclusive_amount', $line_status['errors'], true ) ? ' ndfl-error' : '' ); ?>" data-title="<?php esc_attr_e( 'ATI (€)', 'frais-pro' ); ?>"><span contenteditable="<?php echo esc_attr( $ndf_is_closed ? 'false' : 'true' ); ?>" data-name="row[<?php echo esc_attr( $i ); ?>][tax_inclusive_amount]" ><?php echo esc_html( $ndfl->tax_inclusive_amount ); ?></span></li>
	<li class="tva<?php echo esc_attr( ! empty( $line_status ) && ( false === $line_status['status'] ) && in_array( 'tax_amount', $line_status['errors'], true ) ? ' ndfl-error' : '' ); ?>" data-title="<?php esc_attr_e( 'Recoverable VAT', 'frais-pro' ); ?>"><span contenteditable="<?php echo esc_attr( $ndf_is_closed ? 'false' : 'true' ); ?>" data-name="row[<?php echo esc_attr( $i ); ?>][tax_amount]" ><?php echo esc_html( $ndfl->tax_amount ); ?></span></li>
	<li class="photo" data-title="<?php esc_attr_e( 'Picture', 'frais-pro' ); ?>"><?php do_shortcode( '[wpeo_upload id="' . $ndfl->id . '" field_name="thumbnail_id" model_name="/note_de_frais/ndfl_class" single="true" ]' ); ?></li>
	<li class="action action-ligne">
		<span class="row-status <?php echo esc_attr( ! empty( $line_status ) && ( false === $line_status['status'] ) ? 'ndfl-error' : 'ndfl-ok' ); ?>" ></span>
<?php if ( ! $ndf_is_closed ) : ?>
		<span class="icon ion-trash-a action-attribute"
			data-ndfl-id="<?php echo esc_attr( $ndfl->id ); ?>"
			data-ndf-id="<?php echo esc_attr( $ndf->id ); ?>"
			data-action="delete_ndfl"
			data-display-mode="<?php echo esc_attr( $display_mode ); ?>"
			data-namespace="noteDeFrais"
			data-module="NDFL"
			data-before-method="confirmDeletion"
			data-confirm-text="<?php esc_attr_e( 'Are you sur you want to delete this line? This action can not be revert.', 'frais-pro' ); ?>"
			data-loader="row"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_ndfl' ) ); ?>"></span>
<?php endif; ?>
	</li>
</ul>
