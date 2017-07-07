<?php

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="note">
	<?php if ( ! empty( $group ) ) { ?>
	<input type="hidden" name="id" value="<?php echo $group->id; ?>">
	<input type="hidden" name="group_id" value="<?php echo $group->id; ?>">
	<?php } ?>
	<input type="hidden" name="action" value="modify_note_de_frais">
	<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'modify_note_de_frais' ) ); ?>">
	<div class="container">
		<div class="header">
			<span class="button close"><i class="icon ion-ios-arrow-left"></i></span>
			<h2 class="title"><?php echo $group->title; ?></h2>
			<div class="validation_status toggle list" data-parent="toggle" data-target="content" data-title="En cours">
				<input type="hidden" name="id" value="<?php echo esc_attr( $group->id ); ?>">
				<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'modify_group_ndf' ) ); ?>">
				<input name="action" type="hidden" value="modify_group_ndf"/>
				<input name="validation_status" type="hidden" value="<?php echo esc_html( $group->validation_status ); ?>"/>
				<span class="action">
					<span class="label"><?php echo $group->validation_status; ?></span>
					<i class="icon ion-ios-arrow-down"></i>
				</span>
				<ul class="content">
					<li class="item"><span class="pin-status en-cours"></span>En cours</li>
					<li class="item"><span class="pin-status valide"></span>Validée</li>
					<li class="item"><span class="pin-status paye"></span>Payée</li>
					<li class="item"><span class="pin-status refuse"></span>Refusée</li>
				</ul>
			</div>
			<span class="button export action-attribute"
				data-id="<?php echo esc_attr( $group->id ); ?>"
				data-action="export_note_de_frais"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_note_de_frais' ) ); ?>"><i class="icon ion-share"></i></span>
		</div>

		<div class="content gridwrapper">

			<div class="flex-table">

				<ul class="heading">
					<li class="date">Date</li>
					<li class="libelle">Libellé</li>
					<!--<li class="type">Type de note</li>-->
					<li class="km">Km</li>
					<li class="ttc">TTC (€)</li>
					<?php // <li class="ht">HT (€)</li> ?>
					<li class="tva">TVA récup.</li>
					<li class="photo">Photo</li>
					<li class="action"></li>
				</ul>

				<ul class="row add" data-i="0">
					<li class="date" data-title="Date"><span contenteditable="true" class="date-time" data-name="row[0][date]"><?php echo current_time( 'd/m/Y H:i' ); ?></span></li>
					<li class="libelle" data-title="Libellé"><span contenteditable="true" data-name="row[0][title]"></span></li>
					<li class="type toggle list" data-parent="toggle" data-target="content" data-title="Type de note">
						<input name="category_name" type="hidden" value="Auto"/>
						<span class="action" contenteditable="false">
							<span class="label">Type de note</span>
							<i class="icon ion-ios-arrow-down"></i>
						</span>
						<ul class="content">
							<li class="item">Auto</li>
							<li class="item">Trajet</li>
						</ul>
					</li>
					<li class="km disabled" data-title="Km"><span contenteditable="false" data-name="row[0][distance]">0</span></li>
					<li class="ttc" data-title="TTC (€)"><span contenteditable="true" data-name="row[0][TaxInclusiveAmount]">0</span></li>
					<?php // <li class="ht" data-title="HT (€)"><span contenteditable="true" data-name="row[0][TaxableAmount]">0</span></li> ?>
					<li class="tva" data-title="TVA récup."><span contenteditable="true" data-name="row[0][TaxAmount]">0</span></li>
					<li class="photo" data-title="Photo"><?php do_shortcode( '[eo_upload_button type="ndf"]' ); ?></span></li>
					<li class="action"><span class="icon ion-ios-plus"></span><span class="icon ion-trash-a"></span></li>
				</ul>

				<?php
				$i = 1;
				if ( ! empty( $notes ) ) :
					foreach ( $notes as $ndf ) :
						if ( ! empty( $ndf ) ) {
							\eoxia\View_Util::exec( 'note-de-frais', 'ndf', 'item', array(
								'group' => $group,
								'ndf' => $ndf,
								'i' => $i,
							) );
							$i++;
						}
					endforeach;
				endif;
				?>

			</div>

			<!-- <span class="button blue float right saveNDF" data-parent="note">Mettre à jour</span> -->
			<div class="update">MAJ : <?php echo esc_html( $group->date_modified ); ?></div>

		</div>

	</div>
</div>
