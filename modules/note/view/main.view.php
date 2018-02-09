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

?>
<h1>
	<?php esc_html_e( 'Professionnal fees sheets', 'frais-pro' ); ?>
</h1>

<div class="single-note<?php echo esc_attr( $note_is_closed ? ' is_closed' : '' ); ?><?php echo esc_attr( $has_ko_line ? ' ndf-error' : '' ); ?> <?php echo esc_attr( $display_mode ); ?>" >
	<input type="hidden" name="id" value="<?php echo esc_attr( $note->id ); ?>">
	<input type="hidden" name="action" value="update_note">
	<input type="hidden" name="display_mode" value="<?php echo esc_attr( $display_mode ); ?>">
	<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'update_note' ) ); ?>">

	<div class="container">
		<div class="header">
			<a href="<?php echo esc_url( menu_page_url( \eoxia\Config_Util::$init['frais-pro']->slug, false ) ); ?>" class="close"><i class="icon far fa-chevron-left"></i></a>
			<div class="title">
				<h2><?php echo esc_html( $note->title ); ?></h2>
				<div class="note-last-update" ><?php esc_html_e( 'Last update', 'frais-pro' ); ?> : <?php echo esc_html( $note->date_modified['rendered']['date_human_readable'] ); ?></div>
			</div>
			<div class="validation_status wpeo-dropdown">
				<button class="dropdown-toggle wpeo-button button-main">
					<span class="pin-status <?php echo esc_html( $note->$note_status_taxonomy->slug ); ?>"><?php echo esc_html( $note->$note_status_taxonomy->name ); ?></span>
					<i class="button-icon fas fa-caret-down"></i>
				</button>
				<ul class="dropdown-content">
				<?php foreach ( $status_list as $note_status ) : ?>
					<li data-id="<?php echo esc_attr( $note_status->id ); ?>" class="dropdown-item pin-status <?php echo esc_attr( $note_status->slug ); ?>"><?php echo esc_html( $note_status->name ); ?></li>
				<?php endforeach; ?>
				</ul>
			</div>
			<span class="export toggle list" data-parent="toggle" data-target="content" >
				<?php \eoxia\View_Util::exec( 'frais-pro', 'note', 'actions', array(
					'ndf' => $note,
				) ); ?>
			</span>
		</div>

		<div class="content">
			<div class="note-action">
				<div class="wpeo-button button-blue button-uppercase">
					<i class="button-icon far fa-images"></i> <span><?php esc_html_e( 'Multiple add from pictures', 'frais-pro' ); ?></span>
				</div>

				<div class="wpeo-button button-blue button-uppercase action-attribute"
					data-action="fp_create_line" data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_create_line' ) ); ?>" data-parent-id="<?php echo esc_attr( $note->id ); ?>" >
					<i class="button-icon fas fa-plus-circle"></i> <span><?php esc_html_e( 'New line', 'frais-pro' ); ?></span>
				</div>

				<div class="note-recap">
					<div class="note-ttc">
						<span class="value"><?php echo esc_html( $note->tax_inclusive_amount ); ?></span>
						<span class="currency"><?php esc_html_e( '€', 'frais-pro' ); ?></span>
						<span class="taxe"><?php esc_html_e( 'ATI', 'frais-pro' ); ?></span>
					</div>
					<div class="note-tva">
						<span class="value"><?php echo esc_html( $note->tax_amount ); ?></span>
						<span class="currency"><?php esc_html_e( '€', 'frais-pro' ); ?></span>
						<span class="taxe"><?php esc_html_e( 'VAT', 'frais-pro' ); ?></span>
					</div>
				</div> <!-- .note-recap -->

				<div class="display-method">
					<span class="action-attribute wpeo-button button-square-50 button-grey <?php echo esc_attr( 'grid' === $display_mode ? 'active' : '' );  ?>"
						data-id="<?php echo esc_attr( $note->id ); ?>"
						data-display-mode="grid"
						data-action="open_ndf"
						aria-label="<?php esc_attr_e( 'Grid mode', 'frais-pro' ); ?>"
						data-namespace="noteDeFrais"
						data-module="NDFL"
						data-before-method="beforeDisplayModeChange"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'open_ndf' ) ); ?>"><i class="icon fas fa-th-large"></i></span>
					<span class="action-attribute wpeo-button button-square-50 button-grey <?php echo esc_attr( 'list' === $display_mode ? 'active' : '' );  ?>"
						data-id="<?php echo esc_attr( $note->id ); ?>"
						data-display-mode="list"
						data-action="open_ndf"
						data-namespace="noteDeFrais"
						data-module="NDFL"
						data-before-method="beforeDisplayModeChange"
						aria-label="<?php esc_attr_e( 'List mode', 'frais-pro' ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'open_ndf' ) ); ?>"><i class="icon far fa-list-ul"></i></span>
				</div> <!-- .display-method -->
			</div> <!-- .note-action -->

			<div class="wpeo-table table-flex list-line-header">
				<div class="table-row table-header">
					<div class="table-cell image"><?php esc_html_e( 'Picture', 'frais-pro' ); ?></div>
					<div class="table-cell date"><?php esc_html_e( 'Date', 'frais-pro' ); ?></div>
					<div class="table-cell libelle"><?php esc_html_e( 'Label', 'frais-pro' ); ?></div>
					<div class="table-cell type"><?php esc_html_e( 'Type', 'frais-pro' ); ?></div>
					<div class="table-cell km"><?php esc_html_e( 'Km', 'frais-pro' ); ?></div>
					<div class="table-cell ttc"><?php esc_html_e( 'ATI(€)', 'frais-pro' ); ?></div>
					<div class="table-cell tva"><?php esc_html_e( 'VAT', 'frais-pro' ); ?></div>
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
