<?php
/**
 * Formulaire pour éditer une ligne de note de frais en mode 'liste'.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package NDF
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul class="row" data-i="<?php echo esc_attr( $i ); ?>"<?php echo esc_attr( ! empty( $ndfl ) && ! empty( $ndfl->current_category ) && ! empty( $ndfl->current_category->special_treatment ) ? ' special_treatment=' . $ndfl->current_category->special_treatment : '' ); ?>>
	<input type="hidden" name="id" value="<?php echo esc_attr( $ndfl->id ); ?>">
	<li class="group-date date" data-title="Date" data-namespace="noteDeFrais" data-module="NDFL" data-after-method="changeDate" >
		<input type="text" class="mysql-date" style="width: 0px; padding: 0px; border: none; display: block; height: 0px;" name="date" value="<?php echo esc_attr( $ndfl->date['date_input']['date'] ); ?>" />
		<span contenteditable="true" class="date"><?php echo esc_html( $ndfl->date['date_input']['fr_FR']['date'] ); ?></span>
	</li>
	<li class="libelle" data-title="Libellé"><span contenteditable="true" data-name="row[<?php echo esc_attr( $i ); ?>][title]"><?php echo esc_html( $ndfl->title ); ?></span></li>
	<li class="type toggle list" data-parent="toggle" data-target="content" data-title="Type de note">
		<?php Type_Note_Class::g()->display( $ndfl ); ?>
	</li>
	<li class="km" data-title="Km"><span contenteditable="true" data-name="row[<?php echo esc_attr( $i ); ?>][distance]" ><?php echo esc_html( $ndfl->distance ); ?></span></li>
	<li class="ttc" data-title="TTC (€)"><span contenteditable="true" data-name="row[<?php echo esc_attr( $i ); ?>][tax_inclusive_amount]" ><?php echo esc_html( $ndfl->tax_inclusive_amount ); ?></span></li>
	<li class="tva" data-title="TVA récup."><span contenteditable="true" data-name="row[<?php echo esc_attr( $i ); ?>][tax_amount]" ><?php echo esc_html( $ndfl->tax_amount ); ?></span></li>
	<li class="photo" data-title="Photo"><?php do_shortcode( '[wpeo_upload id="' . $ndfl->id . '" field_name="thumbnail_id" model_name="/note_de_frais/ndfl_class" single="true" mime_type="image" ]' ); ?></li>
	<li class="action action-ligne">
<?php if ( ! $ndf_is_closed ) : ?>
		<span class="icon ion-trash-a action-attribute"
			data-ndfl-id="<?php echo esc_attr( $ndfl->id ); ?>"
			data-ndf-id="<?php echo esc_attr( $ndf->id ); ?>"
			data-action="delete_ndfl"
			data-display-mode="<?php echo esc_attr( $display_mode ); ?>"
			data-namespace="noteDeFrais"
			data-module="NDFL"
			data-before-method="confirmDeletion"
			data-confirm-text="La ligne de saisie ne pourra pas être récupérée"
			data-loader="row"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_ndfl' ) ); ?>"></span>
<?php endif; ?>
	</li>
</ul>
