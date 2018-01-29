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

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$has_ko_line = false;
$line_output = '';

$i = 1;
if ( ! empty( $ndfl ) ) :
	foreach ( $ndfl as $ndfl_single ) :
		if ( ! empty( $ndfl_single ) ) {
			$line_status = Line_Class::g()->check_line_status( $ndfl_single );
			if ( ! empty( $line_status ) && false === $line_status['status'] ) {
				$has_ko_line = true;
			}

			ob_start();
			\eoxia\View_Util::exec( 'frais-pro', 'line', 'item', array(
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

<h1>
	<?php esc_html_e( 'Professionnal fees sheets', 'frais-pro' ); ?>
</h1>

<div class="single-note<?php echo esc_attr( $ndf_is_closed ? ' is_closed' : '' ); ?><?php echo esc_attr( $has_ko_line ? ' ndf-error' : '' ); ?> <?php echo esc_attr( $display_mode ); ?>" >
	<?php if ( ! empty( $ndf ) ) : ?>
	<input type="hidden" name="id" value="<?php echo esc_attr( $ndf->id ); ?>">
	<input type="hidden" name="parent_id" value="<?php echo esc_attr( $ndf->id ); ?>">
	<?php endif; ?>
	<input type="hidden" name="action" value="modify_ndfl">
	<input type="hidden" name="display_mode" value="<?php echo esc_attr( $display_mode ); ?>">
	<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'modify_ndfl' ) ); ?>">

	<div class="container">
		<div class="header">
			<a href="#" class="close"><i class="icon fa fa-angle-left"></i></a>
			<div class="title">
				<h2><?php echo esc_html( $ndf->title ); ?></h2>
				<div class="note-last-update" ><?php esc_html_e( 'Last update', 'frais-pro' ); ?> : <?php echo esc_html( $ndf->date_modified['rendered']['date_human_readable'] ); ?></div>
			</div>
			<div class="validation_status toggle list" data-parent="toggle" data-target="content" data-title="<?php echo esc_attr( $ndf->validation_status ); ?>">
				<input type="hidden" name="id" value="<?php echo esc_attr( $ndf->id ); ?>">
				<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'modify_ndf' ) ); ?>">
				<input name="action" type="hidden" value="modify_ndf"/>
				<input type="hidden" name="display_mode" value="<?php echo esc_attr( $display_mode ); ?>">
				<input name="validation_status" type="hidden" value="<?php echo esc_html( $ndf->validation_status ); ?>"/>

				<div class="wpeo-dropdown">
					<button class="dropdown-toggle wpeo-button button-main">
						<span class="pin-status <?php echo esc_html( $ndf->validation_status ); ?>"><?php echo esc_html( $ndf->validation_status ); ?></span>
						<i class="button-icon fa fa-caret-down"></i>
					</button>
					<ul class="dropdown-content">
					<?php foreach ( Note_Class::g()->get_statuses() as $slug => $label ) : ?>
						<li data-type="<?php echo esc_attr( $slug ); ?>" class="dropdown-item pin-status <?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<span class="export toggle list" data-parent="toggle" data-target="content" >
				<?php \eoxia\View_Util::exec( 'frais-pro', 'note', 'actions', array(
					'ndf' => $ndf,
				) ); ?>
			</span>
		</div>

		<div class="content">
			<?php if ( empty( $user->prixkm ) ) : ?>
				<div class="notice error"><?php echo sprintf( __( 'Your %1$sprice per kilometer%2$s is not setted. Please go to %3$syour profile%4$s in order to set it.', 'frais-pro' ), '<strong>', '</strong>', '<a target="_blank" href="' . esc_url( get_edit_profile_url() ) . '">', '</a>' ); ?></div>
			<?php endif; ?>

			<div class="note-action">
				<div class="wpeo-button button-blue button-uppercase">
					<i class="button-icon fa fa-picture-o"></i> <span>Ajout mutliple à partir d'images</span>
				</div>

				<div class="wpeo-button button-blue button-uppercase">
					<i class="button-icon fa fa-plus-circle"></i> <span>Ajouter une ligne</span>
				</div>

				<div class="note-recap">
					<div class="note-ttc">
						<span class="value"><?php echo esc_html( $ndf->tax_inclusive_amount ); ?></span>
						<span class="currency"><?php esc_html_e( '€', 'frais-pro' ); ?></span>
						<span class="taxe"><?php esc_html_e( 'ATI', 'frais-pro' ); ?></span>
					</div>
					<div class="note-tva">
						<span class="value"><?php echo esc_html( $ndf->tax_amount ); ?></span>
						<span class="currency"><?php esc_html_e( '€', 'frais-pro' ); ?></span>
						<span class="taxe"><?php esc_html_e( 'VAT', 'frais-pro' ); ?></span>
					</div>
				</div> <!-- .note-recap -->

				<div class="display-method">
					<span class="action-attribute wpeo-button button-square-50 button-grey <?php echo esc_attr( 'grid' === $display_mode ? 'active' : '' );  ?>"
						data-id="<?php echo esc_attr( $ndf->id ); ?>"
						data-display-mode="grid"
						data-action="open_ndf"
						aria-label="<?php esc_attr_e( 'Grid mode', 'frais-pro' ); ?>"
						data-namespace="noteDeFrais"
						data-module="NDFL"
						data-before-method="beforeDisplayModeChange"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'open_ndf' ) ); ?>"><i class="icon fa fa-th-large"></i></span>
					<span class="action-attribute wpeo-button button-square-50 button-grey <?php echo esc_attr( 'list' === $display_mode ? 'active' : '' );  ?>"
						data-id="<?php echo esc_attr( $ndf->id ); ?>"
						data-display-mode="list"
						data-action="open_ndf"
						data-namespace="noteDeFrais"
						data-module="NDFL"
						data-before-method="beforeDisplayModeChange"
						aria-label="<?php esc_attr_e( 'List mode', 'frais-pro' ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'open_ndf' ) ); ?>"><i class="icon fa fa-list-ul"></i></span>
				</div> <!-- .display-method -->
			</div> <!-- .note-action -->

			<div class="wpeo-table table-flex list-line-header">
				<div class="table-row table-header">
					<div class="table-cell image">Image</div>
					<div class="table-cell date">Date</div>
					<div class="table-cell libelle">Libellé</div>
					<div class="table-cell type">Type de note</div>
					<div class="table-cell km">Km</div>
					<div class="table-cell ttc">TTC(€)</div>
					<div class="table-cell tva">TVA récup.</div>
					<div class="table-cell status"></div>
					<div class="table-cell action table-end"></div>
				</div>
			</div>

			<div class="wpeo-table table-flex list-line">
				<?php echo $line_output; // WPCS: XSS ok. ?>
			</div>

		</div> <!-- .content -->

	</div> <!-- .container -->
</div> <!-- .single-note -->
