<?php

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<ul class="row" data-i="<?php echo $i; ?>">
	<input type="hidden" name="row[<?php echo $i; ?>][id]" value="<?php echo $ndf->id; ?>">
	<li class="date" data-title="Date"><span contenteditable="true" data-name="row[<?php echo $i; ?>][date]"><?php echo $ndf->date_modified; ?></span></li>
	<li class="libelle" data-title="Libellé"><span contenteditable="true" data-name="row[<?php echo $i; ?>][title]"><?php echo esc_html( $ndf->title ); ?></span></li>
	<li class="type toggle list" data-parent="toggle" data-target="content" data-title="Type de note">
		<input name="category_name" type="hidden" value="<?php echo esc_attr( $ndf->category_name ); ?>"/>
		<span class="action" contenteditable="false">
			<span class="label"><?php echo esc_attr( $ndf->category_name ); ?></span>
			<i class="icon ion-ios-arrow-down"></i>
		</span>
		<ul class="content">
			<li class="item">Auto</li>
			<li class="item">Trajet</li>
		</ul>
	</li>
	<li class="km" data-title="Km"><span contenteditable="true" data-name="row[<?php echo $i; ?>][distance]"></span></li>
	<li class="ttc" data-title="TTC (€)"><span contenteditable="true" data-name="row[<?php echo $i; ?>][TaxInclusiveAmount]"><?php echo esc_html( $ndf->TaxInclusiveAmount ); ?></span></li>
	<li class="ht" data-title="HT (€)"><span contenteditable="true" data-name="row[<?php echo $i; ?>][TaxableAmount]"><?php echo esc_html( $ndf->TaxableAmount ); ?></span></li>
	<li class="tva" data-title="TVA récup."><span contenteditable="true" data-name="row[<?php echo $i; ?>][TaxAmount]"><?php echo esc_html( $ndf->TaxAmount ); ?></span></li>
	<li class="photo" data-title="Photo"><?php do_shortcode( '[eo_upload_button id="' . $ndf->id . '" type="ndf" namespace="note_de_frais"]' ); ?></li>
	<li class="action"><span class="icon ion-trash-a action-attribute"
		data-ndf-id="<?php echo esc_attr( $ndf->id ); ?>"
		data-group-id="<?php echo esc_attr( $group->id ); ?>"
		data-action="delete_note_de_frais"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_note_de_frais' ) ); ?>"></span></li>
</ul>
