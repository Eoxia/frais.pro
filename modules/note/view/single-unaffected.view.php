<?php
/**
 * Affichage d'une note qui contient les lignes désaffectées en view 'single'.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="single-note<?php echo esc_attr( $note_is_closed ? ' is_closed' : '' ); ?> <?php echo esc_attr( $display_mode ); ?>" data-id="<?php echo esc_attr( $note->data['id'] ); ?>" >
	<input type="hidden" name="id" value="<?php echo esc_attr( $note->data['id'] ); ?>" >
	<input type="hidden" name="action" value="update_note" >
	<input type="hidden" name="display_mode" value="<?php echo esc_attr( $display_mode ); ?>" >
	<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'update_note' ) ); ?>" >

	<div class="container">
		<div class="header">
			<a href="<?php echo esc_url( menu_page_url( \eoxia\Config_Util::$init['frais-pro']->slug, false ) ); ?>" class="close"><i class="icon far fa-chevron-left"></i></a>
			<div class="title">
				<h2><?php echo esc_html( $note->data['title'] ); ?></h2>
				<div class="note-last-update" ><?php esc_html_e( 'Last update', 'frais-pro' ); ?> : <?php echo esc_html( $note->data['date_modified']['rendered']['date_human_readable'] ); ?></div>
			</div>
		</div>

		<div class="content">
			<div class="note-action">
				<div class="bloc-reassign wpeo-form wpeo-grid grid-2">
					<input type="hidden" name="action" value="reassign_lines" />
					<?php wp_nonce_field( 'reassign_lines' ); ?>

					<div>
						<div class="wpeo-autocomplete" data-action="fp_search_notes_reassign" data-nonce="<?php echo esc_attr( wp_create_nonce( 'search_notes_reassign' ) ); ?>">
							<input type="hidden" name="selected_note_id" value="" />
							<label class="autocomplete-label" for="mon-autocomplete">
								<i class="autocomplete-icon-before far fa-search"></i>
								<input id="mon-autocomplete" placeholder="<?php esc_attr_e( 'Note name', 'frais-pro' ); ?>" class="autocomplete-search-input" type="text" />
								<span class="autocomplete-icon-after"><i class="far fa-times"></i></span>
							</label>
							<ul class="autocomplete-search-list"></ul>
						</div>
					</div>
					<!-- Search icon -->
					<div>
						<div class="wpeo-button button-blue button-disable"><?php esc_html_e( 'Reassign', 'frais-pro' ); ?></div>
					</div>
				</div>

				<div class="note-recap">

				</div> <!-- Sans cette div, la div.display-method est placé à gauche au lieu d'être à droite. -->

				<div class="display-method">
					<span class="wpeo-button button-square-50 button-grey <?php echo esc_attr( 'grid' === $display_mode ? 'active' : '' ); ?>" data-display-type="grid" ><i class="icon fas fa-th-large"></i></span>
					<span class="wpeo-button button-square-50 button-grey <?php echo esc_attr( 'list' === $display_mode ? 'active' : '' ); ?>" data-display-type="list" ><i class="icon far fa-list-ul"></i></span>
				</div> <!-- .display-method -->
			</div> <!-- .note-action -->

			<?php
			\eoxia\View_Util::exec( 'frais-pro', 'line', 'main', array(
				'note'           => $note,
				'lines'          => $lines,
				'note_is_closed' => $note_is_closed,
			) );
			?>

		</div> <!-- .content -->

	</div> <!-- .container -->
</div> <!-- .single-note -->
