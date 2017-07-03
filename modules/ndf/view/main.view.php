<?php

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="note">
	<div class="container">
		<div class="header">
			<h2 class="title" contenteditable="true"><?php echo $group->title; ?></h2>
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
					<li class="ht">HT (#)</li>
					<li class="tva">TVA récup.</li>
					<li class="photo">Photo</li>
					<li class="action"></li>
				</ul>

				<?php
				if ( ! empty( $notes ) ) :
					foreach ( $notes as $ndf ) :
						\eoxia\View_Util::exec( 'note-de-frais', 'ndf', 'item', array(
							'ndf' => $ndf,
						) );
					endforeach;
				endif;
				?>

				<ul class="row add">
					<li class="date"><span contenteditable="true"><?php echo current_time( 'mysql' ); ?></span></li>
					<li class="libelle"><span contenteditable="true"></span></li>
					<li class="type"><span contenteditable="true"></span></li>
					<li class="km"><span contenteditable="true"></span></li>
					<li class="ttc"><span contenteditable="true"></span></li>
					<li class="ht"><span contenteditable="true"></span></li>
					<li class="tva"><span contenteditable="true"></span></li>
					<li class="photo"><span contenteditable="true"></span></li>
					<li class="action"><span class="icon ion-ios-plus"></span><span class="icon ion-trash-a"></span></li>
				</ul>

			</div>

			<span class="button blue float right action-input" data-parent="note">Mettre à jour</span>

		</div>

	</div>
</div>
