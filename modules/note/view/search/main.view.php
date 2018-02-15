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

<div class="bloc-search wpeo-form form-light wpeo-grid grid-5">
	<input type="hidden" name="action" value="fp_search_notes" />
	<?php wp_nonce_field( 'fp_search_notes' ); ?>

	<!-- Start date -->
	<div>
		<div class="form-element group-date">
			<label class="form-field-container">
				<span class="form-icon"><i class="fal fa-calendar-alt"></i></span>
				<input type="text" class="mysql-date" name="start_date" value="" />
				<input type="text" class="form-field date" placeholder="<?php esc_html_e( 'Start date', 'frais-pro' ); ?>" value="" />
			</label>
		</div>
	</div>
	<!-- End date -->
	<div>
		<div class="form-element group-date">
			<label class="form-field-container">
				<span class="form-icon"><i class="fal fa-calendar-alt"></i></span>
				<input type="text" class="mysql-date" name="end_date" value="" />
				<input type="text" class="form-field date" placeholder="<?php esc_html_e( 'End date', 'frais-pro' ); ?>" value="" />
			</label>
		</div>
	</div>
	<!-- Note status -->
	<div>
		<div class="form-element">
			<label class="form-field-container">
				<?php Note_Status_Class::g()->display( 0, array( 'current_screen' => 'search' ) ); ?>
			</label>
		</div>
	</div>
	<!-- User search -->
	<div>
		<div class="form-element">
			<div class="form-field-container">
				<div class="wpeo-autocomplete autocomplete-light" data-action="fp_search_users" data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_search_users' ) ); ?>">
					<input type="hidden" name="selected_user_id" value="" />
					<label class="autocomplete-label" for="mon-autocomplete">
						<i class="autocomplete-icon-before far fa-search"></i>
						<input id="mon-autocomplete" placeholder="Utilisateur" class="autocomplete-search-input" type="text" />
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
		</div>
	</div>
	<!-- Search icon -->
	<div>
		<div class="action-input wpeo-button button-blue button-square-40" data-parent="bloc-search"><i class="button-icon far fa-search"></i></div>
	</div>
</div>
