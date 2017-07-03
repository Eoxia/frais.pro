<?php

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<ul class="row">
	<input type="hidden" name="row[<?php echo $i; ?>][id]" value="<?php echo $ndf->id; ?>">
	<li class="date" data-title="Date"><span contenteditable="true" data-name="row[<?php echo $i; ?>][date]"><?php echo $ndf->date_modified; ?></span></li>
	<li class="libelle" data-title="Libellé"><span contenteditable="true" data-name="row[<?php echo $i; ?>][title]"><?php echo esc_html( $ndf->title ); ?></span></li>
	<li class="type toggle list" data-title="Type de note">
		<span contenteditable="false" class="action" data-name="row[<?php echo $i; ?>][type]">
			<span class="label">Type de note</span>
			<i class="icon ion-ios-arrow-down"></i>
		</span>
		<ul class="content">
			<li class="item">Auto</li>
			<li class="item">Trajet</li>
		</ul>
	</li>
	<li class="km" data-title="Km"><span contenteditable="true" data-name="row[<?php echo $i; ?>][km]"></span></li>
	<li class="ttc" data-title="TTC (€)"><span contenteditable="true" data-name="row[<?php echo $i; ?>][ttc]"><?php echo esc_html( $ndf->TaxInclusiveAmount ); ?></span></li>
	<li class="ht" data-title="HT (€)"><span contenteditable="true" data-name="row[<?php echo $i; ?>][ht]"><?php echo esc_html( $ndf->TaxableAmount ); ?></span></li>
	<li class="tva" data-title="TVA récup."><span contenteditable="true" data-name="row[<?php echo $i; ?>][tva]"><?php echo esc_html( $ndf->TaxAmount ); ?></span></li>
	<li class="photo" data-title="Photo"><span contenteditable="true" data-name="row[<?php echo $i; ?>][photo]"></span></li>
	<li class="action"><span class="icon ion-trash-a"></span></li>
</ul>
