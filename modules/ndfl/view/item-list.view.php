<?php

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul class="row" data-i="<?php echo $i; ?>">
	<input type="hidden" name="id" value="<?php echo $ndfl->id; ?>">
	<li class="group-date date" data-title="Date">
		<input type="text" class="mysql-date" style="width: 0px; padding: 0px; border: none; display: block; height: 0px;" name="date" value="<?php echo $ndfl->date['date_input']['date']; ?>" />
		<span contenteditable="true" class="date-time"><?php echo $ndfl->date['date_input']['fr_FR']['date_time']; ?></span>
	</li>
	<li class="libelle" data-title="Libellé"><span contenteditable="true" data-name="row[<?php echo $i; ?>][title]"><?php echo esc_html( $ndfl->title ); ?></span></li>
	<li class="type toggle list" data-parent="toggle" data-target="content" data-title="Type de note">
		<input name="category_name" type="hidden" value="<?php echo esc_attr( $ndfl->category_name ); ?>"/>
		<span class="action" contenteditable="false">
			<span class="label"><?php echo esc_attr( $ndfl->category_name ); ?></span>
			<i class="icon ion-ios-arrow-down"></i>
		</span>
		<ul class="content">
			<li class="item">Autre</li>
			<li class="item">Trajet</li>
		</ul>
	</li>
	<li class="km<?php echo $ndfl->category_name == 'Trajet' ? '': ' disabled'; ?>" data-title="Km"><span contenteditable="<?php echo $ndfl->category_name == 'Trajet' ? 'true': 'false'; ?>" data-name="row[<?php echo $i; ?>][distance]" placeholder="0" ><?php echo esc_html( $ndfl->distance ); ?></span></li>
	<li class="ttc<?php echo $ndfl->category_name != 'Trajet' ? '': ' disabled'; ?>" data-title="TTC (€)"><span contenteditable="<?php echo $ndfl->category_name != 'Trajet' ? 'true': 'false'; ?>" data-name="row[<?php echo $i; ?>][tax_inclusive_amount]" placeholder="0" ><?php echo esc_html( $ndfl->tax_inclusive_amount ); ?></span></li>
	<li class="tva<?php echo $ndfl->category_name != 'Trajet' ? '': ' disabled'; ?>" data-title="TVA récup."><span contenteditable="<?php echo $ndfl->category_name != 'Trajet' ? 'true': 'false'; ?>" data-name="row[<?php echo $i; ?>][tax_amount]" placeholder="0" ><?php echo esc_html( $ndfl->tax_amount ); ?></span></li>
	<li class="photo" data-title="Photo"><?php do_shortcode( '[wpeo_upload id="' . $ndfl->id . '" field_name="thumbnail_id" model_name="/note_de_frais/ndfl_class" single="true" mime_type="image" ]' ); ?></li>
	<li class="action action-ligne"><span class="icon ion-trash-a action-attribute"
		data-ndfl-id="<?php echo esc_attr( $ndfl->id ); ?>"
		data-ndf-id="<?php echo esc_attr( $ndf->id ); ?>"
		data-action="delete_ndfl"
		data-namespace="noteDeFrais"
		data-module="NDFL"
		data-before-method="confirmDeletion"
		data-confirm-text="La ligne de saisie ne pourra pas être récupérée"
		data-loader="row"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_ndfl' ) ); ?>"></span></li>
</ul>