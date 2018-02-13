<?php
/**
 * Affichage du tableau ainsi que la ligne pour ajouter une ligne de note de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="single-note<?php echo esc_attr( $note_is_closed ? ' is_closed' : '' ); ?> <?php echo esc_attr( $display_mode ); ?>" data-id="<?php echo esc_attr( $note->id ); ?>" >
	<input type="hidden" name="id" value="<?php echo esc_attr( $note->id ); ?>" >
	<input type="hidden" name="action" value="update_note" >
	<input type="hidden" name="display_mode" value="<?php echo esc_attr( $display_mode ); ?>" >
	<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'update_note' ) ); ?>" >

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
				<?php
				\eoxia\View_Util::exec( 'frais-pro', 'note', 'actions', array(
					'note' => $note,
				) );
				?>
			</span>
		</div>

		<div class="content">
			<div class="note-action">
				<div class="wpeo-button button-blue button-uppercase fraispro-mass-line-creation" data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_create_line_from_picture' ) ); ?>" data-parent-id="<?php echo esc_attr( $note->id ); ?>" >
					<i class="button-icon far fa-images"></i> <span><?php esc_html_e( 'Multiple add from pictures', 'frais-pro' ); ?></span>
				</div>

				<div class="wpeo-button button-blue button-uppercase action-attribute" data-action="fp_create_line" data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_create_line' ) ); ?>" data-parent-id="<?php echo esc_attr( $note->id ); ?>" >
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
					<span class="wpeo-button button-square-50 button-grey <?php echo esc_attr( 'grid' === $display_mode ? 'active' : '' ); ?>" data-display-type="grid" ><i class="icon fas fa-th-large"></i></span>
					<span class="wpeo-button button-square-50 button-grey <?php echo esc_attr( 'list' === $display_mode ? 'active' : '' ); ?>" data-display-type="list" ><i class="icon far fa-list-ul"></i></span>
				</div> <!-- .display-method -->
			</div> <!-- .note-action -->

			<?php \eoxia\View_Util::exec( 'frais-pro', 'line', 'main', array( 'lines' => $lines ) ); ?>

		</div> <!-- .content -->

	</div> <!-- .container -->
</div> <!-- .single-note -->
