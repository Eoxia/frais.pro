<?php
/**
 * Affichage du tableau ainsi que la ligne pour ajouter une ligne de note de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 * @subpackage LigneDeFrais
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$has_ko_line = false;
$line_output = '';
$i = 1;
if ( ! empty( $ndfl ) ) :
	foreach ( $ndfl as $ndfl_single ) :
		if ( ! empty( $ndfl_single ) ) {
			$line_status = NDFL_Class::g()->check_line_status( $ndfl_single );
			if ( ! empty( $line_status ) && false === $line_status['status'] ) {
				$has_ko_line = true;
			}

			ob_start();
			\eoxia\View_Util::exec( 'frais-pro', 'ndfl', 'item-' . $display_mode, array(
				'ndf'           => $ndf,
				'ndfl'          => $ndfl_single,
				'i'             => $i,
				'user'          => $user,
				'display_mode'  => $display_mode,
				'ndf_is_closed' => $ndf_is_closed,
				'line_status'   => $line_status,
			) );
			$line_output .= ob_get_clean();
			$i++;
		}
	endforeach;
endif;

?>

<div class="note <?php echo esc_attr( $ndf_is_closed ? ' is_closed' : '' ); ?><?php echo esc_attr( $has_ko_line ? ' ndf-error' : '' ); ?>">
	<?php if ( ! empty( $ndf ) ) : ?>
	<input type="hidden" name="id" value="<?php echo esc_attr( $ndf->id ); ?>">
	<input type="hidden" name="parent_id" value="<?php echo esc_attr( $ndf->id ); ?>">
	<?php endif; ?>
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
					<?php foreach ( NDF_Class::g()->get_statuses() as $slug => $label ) : ?>
						<li data-type="<?php echo esc_attr( $slug ); ?>" class="item pin-status <?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<span class="export toggle list" data-parent="toggle" data-target="content" >
				<?php \eoxia\View_Util::exec( 'frais-pro', 'ndf', 'toggle-export', array(
					'ndf' => $ndf,
				) ); ?>
			</span>
		</div>

		<div class="content">
			<!-- <span class="button blue float right saveNDF" data-parent="note">Mettre à jour</span> -->
			<div class="update"><?php esc_attr_e( 'Last update', 'frais-pro' ); ?> : <span class="date_modified_value"><?php echo esc_html( $ndf->date_modified['date_human_readable'] ); ?></span></div>

			<?php if ( empty( $user->prixkm ) ) : ?>
				<div class="notice error"><?php echo esc_html( sprintf( __( 'Your %1$sprice per kilometer%2$s is not setted. Please go to %3$syour profile%4$s in order to set it.', 'frais-pro' ), '<strong>', '</strong>', '<a target="_blank" href="' . esc_url( get_edit_profile_url() ) . '">', '</a>' ) ); ?></div>
			<?php endif; ?>

			<div class="display-method">
				<span class="action-attribute button grey <?php echo esc_attr( 'grid' === $display_mode ? 'active' : '' );  ?>"
					data-id="<?php echo esc_attr( $ndf->id ); ?>"
					data-display-mode="grid"
					data-action="open_ndf"
					aria-label="<?php esc_attr_e( 'Grid mode', 'frais-pro' ); ?>"
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
					aria-label="<?php esc_attr_e( 'List mode', 'frais-pro' ); ?>"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'open_ndf' ) ); ?>"><i class="icon ion-ios-list-outline"></i></span>
			</div>

			<div class="flex-table <?php echo esc_attr( $display_mode ); ?>" >

			<?php if ( ! $ndf_is_closed || ( 'list' === $display_mode ) ) : ?>
				<ul class="heading">
					<li class="date"><?php esc_attr_e( 'Date', 'frais-pro' ); ?></li>
					<li class="libelle"><?php esc_attr_e( 'Name', 'frais-pro' ); ?></li>
					<li class="type"><?php esc_attr_e( 'Type', 'frais-pro' ); ?></li>
					<li class="km"><?php esc_attr_e( 'Km', 'frais-pro' ); ?></li>
					<li class="ttc"><?php esc_attr_e( 'ATI (€)', 'frais-pro' ); ?></li>
					<li class="tva"><?php esc_attr_e( 'Recoverable VAT', 'frais-pro' ); ?></li>
					<li class="photo"><?php esc_attr_e( 'Picture', 'frais-pro' ); ?></li>
					<li class="action"></li>
				</ul>
			<?php endif; ?>

			<?php if ( ! $ndf_is_closed ) : ?>
				<ul class="row add" data-i="0" >
					<li class="group-date date" data-title="<?php esc_attr_e( 'Date', 'frais-pro' ); ?>" data-namespace="noteDeFrais" data-module="NDFL" data-after-method="changeDate" >
						<input type="text" class="mysql-date" style="width: 0px; padding: 0px; border: none; display: block; height: 0px;" name="date" value="<?php echo esc_attr( current_time( 'mysql' ) ); ?>" />
						<span contenteditable="true" class="date"><?php echo esc_attr( current_time( 'd/m/Y' ) ); ?></span>
					</li>
					<li class="libelle" data-title="<?php esc_attr_e( 'Name', 'frais-pro' ); ?>"><span contenteditable="true" data-name="row[0][title]"></span></li>
					<li class="type toggle list" data-parent="toggle" data-target="content" data-title="<?php esc_attr_e( 'Line type', 'frais-pro' ); ?>">
						<?php Type_Note_Class::g()->display(); ?>
					</li>
					<li class="km ndfl-placeholder-container" data-title="<?php esc_attr_e( 'Km', 'frais-pro' ); ?>">
						<span contenteditable="true" data-name="row[0][distance]" ></span>
					</li>
					<li class="ttc ndfl-placeholder-container" data-title="<?php esc_attr_e( 'ATI (€)', 'frais-pro' ); ?>">
						<span contenteditable="true" data-name="row[0][tax_inclusive_amount]" ></span>
					</li>
					<li class="tva ndfl-placeholder-container" data-title="<?php esc_attr_e( 'Recoverable VAT', 'frais-pro' ); ?>">
						<span contenteditable="true" data-name="row[0][tax_amount]" ></span>
					</li>
					<li class="photo" data-title="<?php esc_attr_e( 'Picture', 'frais-pro' ); ?>"><?php do_shortcode( '[wpeo_upload model_name="/note_de_frais/NDFL_Class" single="true" field_name="thumbnail_id"]' ); ?></span></li>
					<li class="action action-ligne"><span class="icon ion-ios-plus"></span></li>
				</ul>
				<div class="wpeo-button button-primary fraispro-mass-line-creation alignright" data-ndf-id="<?php echo esc_attr( $ndf->id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'fraispro_create_line_from_picture' ) ); ?>" >
					<i class="button-icon fa fa-picture-o"></i>
					<span><?php esc_html_e( 'Create lines from picture', 'frais-pro' ); ?></span>
				</div>
				<div class="clear" ></div>
			<?php endif; ?>

			<?php echo $line_output; // WPCS: XSS ok. ?>

			</div>
		</div>

	</div>
</div>
