<?php
/**
 * Vue principale de l'application
 *
 * @package Eoxia\Plugin
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<section class="eox-note-frais">

	<h1>Mes notes de frais</h1>
	<div class="add-ndf button blue action-attribute"
			data-action="create_group_ndf"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_group_ndf' ) ); ?>"><i class="icon ion-plus-round"></i><span>Ajout</span></div>

	<div class="main-container">
		<div class="liste-note gridwrapper w3">
			<?php
			if ( ! empty( $groups_ndf ) ) :
				foreach ( $groups_ndf as $ndf ) :
					\eoxia\View_Util::exec( 'note-de-frais', 'group-ndf', 'item', array(
						'ndf' => $ndf,
					) );
				endforeach;
			endif;
			?>
		</div>

		<div class="single-note">

		</div>
	</div>

</section>
