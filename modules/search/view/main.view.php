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

<div class="bloc-search wpeo-form form-light wpeo-grid grid-6">

	<!-- Start date -->
	<div>
		<div class="form-element group-date">
			<label class="form-field-container">
				<span class="form-field-icon-prev"><i class="fas fa-calendar-alt"></i></span>
				<input type="hidden" class="mysql-date" name="start_date" value="" />
				<input type="text" class="form-field date" placeholder="<?php esc_html_e( 'Start date', 'frais-pro' ); ?>" value="" />
			</label>
		</div>
	</div>
	<!-- End date -->
	<div>
		<div class="form-element group-date">
			<label class="form-field-container">
				<span class="form-field-icon-prev"><i class="fas fa-calendar-alt"></i></span>
				<input type="hidden" class="mysql-date" name="end_date" value="" />
				<input type="text" class="form-field date" placeholder="<?php esc_html_e( 'End date', 'frais-pro' ); ?>" value="" />
			</label>
		</div>
	</div>
	<!-- Note status -->
	<div>
		<div class="form-element">
			<?php Note_Status_Class::g()->display( null, array( 'current_screen' => 'search' ) ); ?>
		</div>
	</div>
	<!-- User search -->
	<div>
		<div class="form-element">
			<div class="form-field-container">
				<div class="wpeo-autocomplete autocomplete-light" data-action="fp_search_users" data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_search_users' ) ); ?>">
					<input type="hidden" name="selected_user_id" value="" />
					<label class="autocomplete-label" for="autocomplete-search-users">
						<i class="autocomplete-icon-before fas fa-search"></i>
						<input id="autocomplete-search-users" placeholder="Utilisateur" class="autocomplete-search-input" type="text" />
						<span class="autocomplete-icon-after"><i class="far fa-times"></i></span>
					</label>
					<ul class="autocomplete-search-list"></ul>
				</div>
			</div>
		</div>
	</div>
	<!-- Include Archives -->
	<div>
		<div class="form-element form-align-horizontal">
			<div class="form-field-container">
				<div class="form-field-inline">
					<input type="checkbox" class="form-field" name="include_archives" value="yes" id="search_include_archives" >
					<label for="search_include_archives" ><?php esc_html_e( 'View also archives', 'frais-pro' ); ?></label>
				</div>
			</div>
		</div>
	</div>
	<!-- Search icon -->
	<div>
		<div data-action="fp_search_notes"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_search_notes' ) ); ?>"
			data-parent="bloc-search"
			class="action-input wpeo-button button-blue button-square-40">
			<i class="button-icon fas fa-search"></i>
		</div>
	</div>
</div>
