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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<section class="eox-note-frais">

	<h1>Mes notes de frais <?php echo ! empty( $status ) && 'archive' === $status ? 'archivées' : ''; ?></h1>

	<?php if ( ! empty( $status ) && is_array( $status ) && in_array( 'publish', $status, true ) ) : ?>
		<div class="add-ndf button blue action-attribute"
				data-action="create_ndf"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_ndf' ) ); ?>"><i class="icon ion-plus-round"></i><span>Ajout</span></div>
	<?php endif; ?>

	<div class="main-container">
		<div class="liste-note gridwrapper w3">
			<?php
			if ( ! empty( $ndfs ) ) :
				foreach ( $ndfs as $ndf ) :
					\eoxia\View_Util::exec( 'note-de-frais', 'ndf', 'item', array(
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
