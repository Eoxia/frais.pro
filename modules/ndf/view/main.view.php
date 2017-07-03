<?php

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="note">
	<input type="hidden" name="id" value="<?php echo $group->id; ?>">
	<div class="container">
		<div class="header">
			<h2 class="title" contenteditable="true" data-name="title"><?php echo $group->title; ?></h2>
			<span class="button export"><i class="icon ion-share"></i></span>
			<span class="button close"><i class="icon ion-ios-close-empty"></i></span>
		</div>

		<div class="content gridwrapper">

			<div class="flex-table">

				<ul class="heading">
					<li class="date">Date</li>
					<li class="libelle">Libellé</li>
					<li class="type">Type de note</li>
					<li class="km">Km</li>
					<li class="ttc">TTC (€)</li>
					<li class="ht">HT (€)</li>
					<li class="tva">TVA récup.</li>
					<li class="photo">Photo</li>
					<li class="action"></li>
				</ul>

				<?php
				if ( ! empty( $notes ) ) :
					$i = 0;
					foreach ( $notes as $ndf ) :
						\eoxia\View_Util::exec( 'note-de-frais', 'ndf', 'item', array(
							'ndf' => $ndf,
						) );
						$i++;
					endforeach;
				endif;
				?>

				<ul class="row add" data-i="<?php echo $i; ?>">
					<li class="date" data-title="Date"><span contenteditable="true" data-default-value="<?php echo current_time( 'mysql' ); ?>"><?php echo current_time( 'mysql' ); ?></span></li>
					<li class="libelle" data-title="Libellé"><span contenteditable="true"></span></li>
					<li class="type" data-title="Type de note"><span contenteditable="true"></span></li>
					<li class="km" data-title="Km"><span contenteditable="true"></span></li>
					<li class="ttc" data-title="TTC (€)"><span contenteditable="true"></span></li>
					<li class="ht" data-title="HT (€)"><span contenteditable="true"></span></li>
					<li class="tva" data-title="TVA récup."><span contenteditable="true"></span></li>
					<li class="photo" data-title="Photo"><span contenteditable="true"></span></li>
					<li class="action"><span class="icon ion-ios-plus"></span><span class="icon ion-trash-a"></span></li>
				</ul>

			</div>

			<span class="button blue float right saveNDF" data-parent="note">Mettre à jour</span>

		</div>

	</div>
</div>
