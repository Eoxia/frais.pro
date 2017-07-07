<?php

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<ul class="row" data-i="<?php echo $i; ?>">
	<input type="hidden" name="id" value="<?php echo $ndf->id; ?>">
	<li class="date" data-title="Date"><span contenteditable="true" class="date-time" data-name="row[<?php echo $i; ?>][date]"><?php echo $ndf->date; ?></span></li>
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
	<li class="km<?php echo $ndf->category_name == 'Trajet' ? '': ' disabled'; ?>" data-title="Km"><span contenteditable="<?php echo $ndf->category_name == 'Trajet' ? 'true': 'false'; ?>" data-name="row[<?php echo $i; ?>][distance]"><?php echo esc_html( $ndf->distance ); ?></span></li>
	<li class="ttc<?php echo $ndf->category_name != 'Trajet' ? '': ' disabled'; ?>" data-title="TTC (€)"><span contenteditable="<?php echo $ndf->category_name != 'Trajet' ? 'true': 'false'; ?>" data-name="row[<?php echo $i; ?>][TaxInclusiveAmount]"><?php echo esc_html( $ndf->TaxInclusiveAmount ); ?></span></li>
	<?php // <li class="ht<?php echo $ndf->category_name != 'Trajet' ? '': ' disabled'; " data-title="HT (€)"><span contenteditable="<?php echo $ndf->category_name != 'Trajet' ? 'true': 'false'; " data-name="row[<?php echo $i; ][TaxableAmount]"><?php echo esc_html( $ndf->TaxableAmount ); </span></li> ?>
	<li class="tva<?php echo $ndf->category_name != 'Trajet' ? '': ' disabled'; ?>" data-title="TVA récup."><span contenteditable="<?php echo $ndf->category_name != 'Trajet' ? 'true': 'false'; ?>" data-name="row[<?php echo $i; ?>][TaxAmount]"><?php echo esc_html( $ndf->TaxAmount ); ?></span></li>
	<li class="photo" data-title="Photo"><?php do_shortcode( '[eo_upload_button id="' . $ndf->id . '" type="ndf" namespace="note_de_frais"]' ); ?></li>
	<li class="action"><span class="icon ion-trash-a action-attribute"
		data-ndf-id="<?php echo esc_attr( $ndf->id ); ?>"
		data-group-id="<?php echo esc_attr( $group->id ); ?>"
		data-action="delete_note_de_frais"
		data-namespace="noteDeFrais"
		data-module="NDF"
		data-before-method="confirmDeletion"
		data-confirm-text="Confirmer"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_note_de_frais' ) ); ?>"></span></li>
</ul>
