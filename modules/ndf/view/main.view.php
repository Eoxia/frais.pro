<?php
/**
 * Vue principale de l'application
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<section class="eox-note-frais">

	<h1><?php echo esc_html( sprintf( __( 'My %s professionnal fees sheet', 'frais-pro' ), ( ! empty( $status ) && 'archive' === $status ? __( 'archived', 'frais-pro' ) : '' ) ) ); ?></h1>

	<?php if ( ! empty( $status ) && is_array( $status ) && in_array( 'publish', $status, true ) ) : ?>
		<div class="add-ndf button blue action-attribute"
				data-action="create_ndf"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_ndf' ) ); ?>"><i class="icon ion-plus-round"></i><span><?php esc_html_e( 'Add' ); ?></span></div>
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
