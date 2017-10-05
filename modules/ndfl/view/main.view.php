<?php
/**
 * Affichage du tableau ainsi que la ligne pour ajouter une ligne de note de frais.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package NDF
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="note <?php echo esc_attr( $ndf_is_closed ? ' is_closed' : '' ); ?>">
	<?php if ( ! empty( $ndf ) ) { ?>
	<input type="hidden" name="id" value="<?php echo esc_attr( $ndf->id ); ?>">
	<input type="hidden" name="parent_id" value="<?php echo esc_attr( $ndf->id ); ?>">
	<?php } ?>
	<input type="hidden" name="action" value="modify_ndfl">
	<input type="hidden" name="display_mode" value="<?php echo esc_attr( $display_mode ); ?>">
	<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'modify_ndfl' ) ); ?>">
	<div class="container">
		<div class="header">
			<span class="button close"><i class="icon ion-ios-arrow-left"></i></span>
			<h2 class="title"><?php echo esc_html( $ndf->title ); ?></h2>
			<div class="validation_status toggle list" data-parent="toggle" data-target="content" data-title="<?php echo esc_attr( $ndf->validation_status ); ?>">
				<input type="hidden" name="id" value="<?php echo esc_attr( $ndf->id ); ?>">
				<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'modify_ndf' ) ); ?>">
				<input name="action" type="hidden" value="modify_ndf"/>
				<input name="validation_status" type="hidden" value="<?php echo esc_html( $ndf->validation_status ); ?>"/>
				<span class="action">
					<span class="label pin-status <?php echo esc_attr( NDF_Class::g()->get_status( $ndf->validation_status ) ); ?>"><?php echo esc_html( $ndf->validation_status ); ?></span>
					<i class="icon ion-ios-arrow-down"></i>
				</span>
				<ul class="content">
					<li data-type="en-cours" class="item pin-status en-cours">En cours</li>
					<li data-type="valide"class="item pin-status valide">Validée</li>
					<li data-type="paye" class="item pin-status paye">Payée</li>
					<li data-type="refuse" class="item pin-status refuse">Refusée</li>
				</ul>
			</div>
			<span class="export toggle list" data-parent="toggle" data-target="content">
				<?php \eoxia\View_Util::exec( 'note-de-frais', 'ndf', 'toggle-export', array(
					'ndf' => $ndf,
				) ); ?>
			</span>
		</div>

		<div class="content">

			<?php echo empty( $user->prixkm ) ? '<div class="notice error">Votre <strong>prix/km</strong> n\'est pas configuré, veuillez modifier votre <a target="_blank" href="' . esc_url( get_edit_profile_url() ) . '">profil</a>.</div>' : ''; ?>

			<div class="display-method">
				<span class="action-attribute button grey <?php echo esc_attr( 'grid' === $display_mode ? 'active' : '' );  ?>"
					data-id="<?php echo esc_attr( $ndf->id ); ?>"
					data-display-mode="grid"
					data-action="open_ndf"
					aria-label="<?php esc_attr_e( 'Mode grille', 'note-de-frais' ); ?>"
					data-namespace="noteDeFrais"
					data-module="NDFL"
					data-before-method="beforeDisplayModeChange"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'open_ndf' ) ); ?>"><i class="icon ion-grid"></i></span>
				<span class="action-attribute button grey <?php echo esc_attr( 'list' === $display_mode ? 'active' : '' );  ?>"
					data-id="<?php echo esc_attr( $ndf->id ); ?>"
					data-display-mode="list"
					data-action="open_ndf"
					data-namespace="noteDeFrais"
					data-module="NDFL"
					data-before-method="beforeDisplayModeChange"
					aria-label="<?php esc_attr_e( 'Mode liste', 'note-de-frais' ); ?>"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'open_ndf' ) ); ?>"><i class="icon ion-ios-list-outline"></i></span>
			</div>

			<div class="flex-table <?php echo esc_attr( $display_mode ); ?>" >

				<?php if ( ! $ndf_is_closed ) : ?>
					<ul class="heading">
						<li class="date">Date</li>
						<li class="libelle">Libellé</li>
						<li class="type">Type de note</li>
						<li class="km">Km</li>
						<li class="ttc">TTC (€)</li>
						<li class="tva">TVA récup.</li>
						<li class="photo">Photo</li>
						<li class="action"></li>
					</ul>

				<ul class="row add" data-i="0">
					<li class="group-date date" data-title="Date" data-namespace="noteDeFrais" data-module="NDFL" data-after-method="changeDate" >
						<input type="text" class="mysql-date" style="width: 0px; padding: 0px; border: none; display: block; height: 0px;" name="date" value="<?php echo esc_attr( current_time( 'mysql' ) ); ?>" />
						<span contenteditable="true" class="date"><?php echo esc_attr( current_time( 'd/m/Y' ) ); ?></span>
					</li>
					<li class="libelle" data-title="Libellé"><span contenteditable="true" data-name="row[0][title]"></span></li>
					<li class="type toggle list" data-parent="toggle" data-target="content" data-title="Type de note">
						<?php Type_Note_Class::g()->display(); ?>
					</li>
					<li class="km ndfl-placeholder-container" data-title="Km">
						<span class="ndfl-placeholder">0</span>
						<span contenteditable="true" data-name="row[0][distance]" placeholder="0" ></span>
					</li>
					<li class="ttc ndfl-placeholder-container" data-title="TTC (€)">
						<span class="ndfl-placeholder">0</span>
						<span contenteditable="true" data-name="row[0][tax_inclusive_amount]" placeholder="0" ></span>
					</li>
					<li class="tva ndfl-placeholder-container" data-title="TVA récup.">
						<span class="ndfl-placeholder">0</span>
						<span contenteditable="true" data-name="row[0][tax_amount]" placeholder="0" ></span>
					</li>
					<li class="photo" data-title="Photo"><?php do_shortcode( '[wpeo_upload model_name="/note_de_frais/NDFL_Class" single="true" field_name="thumbnail_id"]' ); ?></span></li>
					<li class="action action-ligne"><span class="icon ion-ios-plus"></span></li>
				</ul>
			<?php endif; ?>

				<?php
				$i = 1;
				if ( ! empty( $ndfl ) ) :
					foreach ( $ndfl as $ndfl_single ) :
						if ( ! empty( $ndfl_single ) ) {
							\eoxia\View_Util::exec( 'note-de-frais', 'ndfl', 'item-' . $display_mode, array(
								'ndf' => $ndf,
								'ndfl' => $ndfl_single,
								'i' => $i,
								'user' => $user,
								'display_mode' => $display_mode,
								'ndf_is_closed' => $ndf_is_closed,
							) );
							$i++;
						}
					endforeach;
				endif;
				?>

			</div>

			<!-- <span class="button blue float right saveNDF" data-parent="note">Mettre à jour</span> -->
			<div class="update">MAJ : <span class="date_modified_value"><?php echo esc_html( $ndf->date_modified['date_human_readable'] ); ?></span></div>

		</div>

	</div>
</div>
