<?php
/**
 * Formulaire pour éditer une ligne de note de frais en mode 'grille'.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 * @subpackage LigneDeFrais
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="table-row line" data-id="<?php echo esc_attr( $line->data['id'] ); ?>" >
	<?php echo apply_filters( 'fp_filter_line_item_before', '', $line );  // WPCS: XSS ok. ?>

	<div class="table-cell line-image">
		<?php do_shortcode( '[wpeo_upload id="' . $line->data['id'] . '" model_name="/frais_pro/Line_Class" single="true" size="full" custom_class="media-grid" mode="' . $mode . '"]' ); ?>
		<?php do_shortcode( '[wpeo_upload id="' . $line->data['id'] . '" model_name="/frais_pro/Line_Class" single="true" custom_class="media-list" mode="' . $mode . '"]' ); ?>
	</div>
	<div class="table-cell line-content wpeo-form">
		<!-- Libelle -->
		<div class="libelle form-element <?php echo esc_attr( Line_Class::g()->check_field_status( $line, 'libelle', $note_is_closed ) ); ?>" >
			<span class="form-label"><?php esc_attr_e( 'Label', 'frais-pro' ); ?></span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="title" value="<?php echo esc_html( ! empty( $line->data['title'] ) ); ?>" <?php echo ( $note_is_closed ? 'readonly="readonly"' : '' ); ?>/>
			</label>
		</div>
		<!-- Date -->
		<div class="form-element date group-date <?php echo esc_attr( Line_Class::g()->check_field_status( $line, 'date', $note_is_closed ) ); ?>" >
			<span class="form-label"><?php esc_attr_e( 'Date', 'frais-pro' ); ?></span>
			<label class="form-field-container">
				<span class="form-icon"><i class="fal fa-calendar-alt"></i></span>
				<input type="hidden" class="mysql-date" name="date" value="<?php echo esc_attr( $line->data['date']['raw'] ); ?>" <?php echo ( $note_is_closed ? 'readonly="readonly"' : '' ); ?>/>
				<input type="text" class="date form-field" value="<?php echo esc_html( $line->data['date']['rendered']['date'] ); ?>" <?php echo ( $note_is_closed ? 'readonly="readonly"' : '' ); ?> />
			</label>
		</div>
		<!-- Type de ligne - Dropdown -->
		<div class="type form-element <?php echo esc_attr( Line_Class::g()->check_field_status( $line, 'current_category', $note_is_closed ) ); ?>" >
			<span class="form-label"><?php esc_attr_e( 'Line type', 'frais-pro' ); ?></span>
			<label class="form-field-container">
				<?php
					Line_Type_Class::g()->display( $line_type_id, array(
						'class' => array(
							( $note_is_closed ? 'button-disable' : '' ),
						),
					) );
				?>
			</label>
		</div>
		<!-- Km -->
		<div class="km distance form-element <?php echo esc_attr( Line_Class::g()->check_field_status( $line, 'distance', $note_is_closed ) ); ?>" >
			<span class="form-label"><?php esc_attr_e( 'Km', 'frais-pro' ); ?></span>
			<label class="form-field-container">
				<input type="text" name="distance" class="form-field" value="<?php echo esc_html( $line->data['distance'] ); ?>" <?php echo ( $note_is_closed || Line_Class::g()->check_amount_input_status( $line, 'km_calculation' ) ? 'readonly="readonly"' : '' ); ?>/>
			</label>
		</div>
		<!-- TTC -->
		<div class="ttc form-element <?php echo esc_attr( Line_Class::g()->check_field_status( $line, 'tax_inclusive_amount', $note_is_closed ) ); ?>" >
			<span class="form-label"><?php esc_attr_e( 'ATI(€)', 'frais-pro' ); ?></span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="tax_inclusive_amount" value="<?php echo esc_html( $line->data['tax_inclusive_amount'] ); ?>" <?php echo ( $note_is_closed || Line_Class::g()->check_amount_input_status( $line ) ? 'readonly="readonly"' : '' ); ?>/>
			</label>
		</div>
		<!-- TVA -->
		<div class="tva form-element <?php echo esc_attr( Line_Class::g()->check_field_status( $line, 'tax_amount', $note_is_closed ) ); ?>" >
			<span class="form-label"><?php esc_attr_e( 'VAT', 'frais-pro' ); ?></span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="tax_amount" value="<?php echo esc_html( $line->data['tax_amount'] ); ?>" <?php echo ( $note_is_closed || Line_Class::g()->check_amount_input_status( $line ) ? 'readonly="readonly"' : '' ); ?> />
			</label>
		</div>
		<!-- Status -->
		<div class="status wpeo-tooltip-event" aria-label="<?php echo esc_attr( ! empty( $line->data['line_status'] ) && ( false === $line->data['line_status']['status'] ) ? __( 'Invalid line', 'frais-pro' ) : __( 'Valid line', 'frais-pro' ) ); ?>">
			<span class="pin <?php echo esc_attr( ! empty( $line->data['line_status'] ) && ( false === $line->data['line_status']['status'] ) ? 'line-error' : 'line-ok' ); ?>"></span>
		</div>
		<!-- Ligne action -->
		<div class="action">
		<?php if ( ! $note_is_closed ) : ?>
			<div class="wpeo-dropdown dropdown-right">
				<button class="dropdown-toggle wpeo-button button-transparent wpeo-tooltip-eventwpeo-tooltip-event" aria-label="<?php esc_html_e( 'Options', 'frais-pro' ); ?>"><i class="button-icon far fa-ellipsis-v"></i></button>
				<ul class="dropdown-content">
					<?php echo apply_filters( 'fp_filter_line_item_action_before', '', $line ); // WPCS: XSS ok. ?>
					<li class="dropdown-item action-delete"
						data-message-delete="<?php esc_html_e( 'Are you sure you want to delete this line', 'frais-pro' ); ?>"
						data-id="<?php echo esc_attr( $line->data['id'] ); ?>"
						data-action="<?php echo esc_attr( 'fp_delete_line' ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_delete_line' ) ); ?>" ><i class="dropdown-icon fas fa-trash-alt fa-fw"></i> <?php esc_html_e( 'Delete', 'frais-pro' ); ?></li>
				</ul>
			</div>
		<?php endif; ?>
		</div>
	</div> <!-- .line-content -->
</div>
