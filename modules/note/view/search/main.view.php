<?php
/**
 * Search note view.
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

<div class="bloc-search wpeo-form wpeo-grid grid-5">
	<input type="hidden" name="action" value="fp_search_notes" />
	<?php wp_nonce_field( 'fp_search_notes' ); ?>

	<div>
		<div class="form-element group-date">
			<input type="text" class="mysql-date" style="width: 0px; padding: 0px; border: none;" name="start_date" value="<?php echo esc_attr( current_time( 'mysql' ) ); ?>" />
			<input type="text" class="date" placeholder="<?php esc_html_e( 'Start date', 'frais-pro' ); ?>" value="" />
		</div>
	</div>
	<div>
		<div class="form-element group-date">
			<input type="text" class="mysql-date" style="width: 0px; padding: 0px; border: none;" name="end_date" value="<?php echo esc_attr( current_time( 'mysql' ) ); ?>" />
			<input type="text" class="date" placeholder="<?php esc_html_e( 'End date', 'frais-pro' ); ?>" value="" />
		</div>
	</div>

	<div>
		<div class="wpeo-dropdown">
			<input type="hidden" name="selected_status_id" />
			<div class="dropdown-toggle wpeo-button button-transparent">
				<span class="pin-status"><?php esc_html_e( 'Status', 'frais-pro' ); ?></span>
				<i class="button-icon fas fa-caret-down"></i>
			</div>
			<ul class="dropdown-content">
				<?php foreach ( $status_list as $note_status ) : ?>
					<li data-id="<?php echo esc_attr( $note_status->id ); ?>" class="dropdown-item pin-status" color="<?php echo esc_attr( $note_status->color ); ?>" ><?php echo esc_html( $note_status->name ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

	<div>
		<div class="wpeo-autocomplete" data-action="fp_search_users" data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_search_users' ) ); ?>">
			<input type="hidden" name="selected_user_id" />
			<label class="autocomplete-label" for="mon-autocomplete">
				<i class="autocomplete-icon-before far fa-search"></i>
				<input id="mon-autocomplete" placeholder="Recherche..." class="autocomplete-search-input" type="text" />
				<span class="autocomplete-icon-after"><i class="far fa-times"></i></span>
			</label>
			<ul class="autocomplete-search-list">
				<li class="autocomplete-result">
					<div class="autocomplete-result-container">
						<span class="autocomplete-result-title">Résultat 1</span>
					</div>
				</li>
				<li class="autocomplete-result">
					<div class="autocomplete-result-container">
						<span class="autocomplete-result-title">Résultat 1</span>
					</div>
				</li>
			</ul>
		</div>
	</div>

	<div>
		<div class="action-input wpeo-button button-square-50"
			data-parent="bloc-search"><i class="button-icon fas fa-heart"></i></div>
	</div>
</div>
