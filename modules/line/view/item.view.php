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
<div class="table-row line" data-id="<?php echo esc_attr( $line->id ); ?>" >
	<?php apply_filters( 'fp_filter_line_item_before', '', $note, $line ); ?>

	<div class="table-cell line-image">
		<?php do_shortcode( '[wpeo_upload id="' . $line->id . '" model_name="/frais_pro/Line_Class" single="true" size="full" custom_class="media-grid" mode="' . $mode . '"]' ); ?>
		<?php do_shortcode( '[wpeo_upload id="' . $line->id . '" model_name="/frais_pro/Line_Class" single="true" custom_class="media-list" mode="' . $mode . '"]' ); ?>
	</div>
	<div class="table-cell line-content wpeo-form">
		<!-- Libelle -->
		<div class="libelle form-element <?php echo esc_attr( ! empty( $line->line_status ) && ( false === $line->line_status['status'] ) && in_array( 'title', $line->line_status['errors'], true ) ? 'input-error' : '' ); ?>">
			<span class="form-label"><?php esc_attr_e( 'Label', 'frais-pro' ); ?></span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="title" placeholder="<?php esc_attr_e( 'Label', 'frais-pro' ); ?>" value="<?php echo esc_html( $line->title ); ?>" <?php echo ( $note_is_closed ? 'readonly="readonly"' : '' ); ?>/>
			</label>
		</div>
		<!-- Date -->
		<div class="form-element date group-date">
			<span class="form-label"><?php esc_attr_e( 'Date', 'frais-pro' ); ?></span>
			<label class="form-field-container">
				<span class="form-icon"><i class="fal fa-calendar-alt"></i></span>
				<input type="text" class="mysql-date" name="date" value="<?php echo esc_attr( $line->date['raw'] ); ?>" <?php echo ( $note_is_closed ? 'readonly="readonly"' : '' ); ?>/>
				<input type="text" class="date form-field" placeholder="<?php echo esc_html( $line->date['rendered']['date'] ); ?>" value="<?php echo esc_html( $line->date['rendered']['date'] ); ?>" <?php echo ( $note_is_closed ? 'readonly="readonly"' : '' ); ?> />
			</label>
		</div>
		<!-- Type de ligne - Dropdown -->
		<div class="type form-element <?php echo esc_attr( ! empty( $line->line_status ) && ( false === $line->line_status['status'] ) && in_array( 'category', $line->line_status['errors'], true ) ? 'input-error' : '' ); ?>">
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
		<div class="km form-element">
			<span class="form-label"><?php esc_attr_e( 'Km', 'frais-pro' ); ?></span>
			<label class="form-field-container">
				<input type="text" name="distance" class="form-field" value="<?php echo esc_html( $line->distance ); ?>" <?php echo ( $note_is_closed || ( ! empty( $line->taxonomy ) && ! empty( $line->taxonomy[ Line_Type_Class::g()->get_type() ] ) && ! empty( $line->taxonomy[ Line_Type_Class::g()->get_type() ][0] ) && empty( $line->taxonomy[ Line_Type_Class::g()->get_type() ][0]->special_treatment ) ) ? 'readonly="readonly"' : '' ); ?>/>
			</label>
		</div>
		<!-- TTC -->
		<div class="ttc form-element <?php echo esc_attr( ! empty( $line->line_status ) && ( false === $line->line_status['status'] ) && in_array( 'amount', $line->line_status['errors'], true ) ? 'input-error' : '' ); ?>">
			<span class="form-label"><?php esc_attr_e( 'ATI(€)', 'frais-pro' ); ?></span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="tax_inclusive_amount" value="<?php echo esc_html( $line->tax_inclusive_amount ); ?>" <?php echo ( $note_is_closed ? 'readonly="readonly"' : '' ); ?>/>
			</label>
		</div>
		<!-- TVA -->
		<div class="tva form-element <?php echo esc_attr( ! empty( $line->line_status ) && ( false === $line->line_status['status'] ) && in_array( 'amount', $line->line_status['errors'], true ) ? 'input-error' : '' ); ?>">
			<span class="form-label"><?php esc_attr_e( 'VAT', 'frais-pro' ); ?></span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="tax_amount" value="<?php echo esc_html( $line->tax_amount ); ?>" <?php echo ( $note_is_closed ? 'readonly="readonly"' : '' ); ?> />
			</label>
		</div>
		<!-- Status -->
		<div class="status wpeo-tooltip-event" aria-label="<?php echo esc_attr( ! empty( $line->line_status ) && ( false === $line->line_status['status'] ) ? __( 'Invalid line', 'frais-pro' ) : __( 'Valid line', 'frais-pro' ) ); ?>">
			<span class="pin <?php echo esc_attr( ! empty( $line->line_status ) && ( false === $line->line_status['status'] ) ? 'line-error' : 'line-ok' ); ?>"></span>
		</div>
		<!-- Ligne action -->
		<div class="action">
		<?php if ( ! $note_is_closed ) : ?>
			<div class="wpeo-dropdown dropdown-right">
				<button class="dropdown-toggle wpeo-button button-transparent wpeo-tooltip-eventwpeo-tooltip-event" aria-label="<?php esc_html_e( 'Options', 'frais-pro' ); ?>"><i class="button-icon far fa-ellipsis-v"></i></button>
				<ul class="dropdown-content">
					<?php apply_filters( 'fp_filter_line_item_action_before', '', $line ); ?>
					<li class="dropdown-item action-delete"
						data-message-delete="<?php esc_html_e( 'Are you sure you want to delete this line', 'frais-pro' ); ?>"
						data-id="<?php echo esc_attr( $line->id ); ?>"
						data-action="<?php echo esc_attr( 'fp_delete_line' ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_delete_line' ) ); ?>" ><i class="dropdown-icon fas fa-trash-alt fa-fw"></i> <?php esc_html_e( 'Delete', 'frais-pro' ); ?></li>
				</ul>
			</div>
		<?php endif; ?>
		</div>
	</div> <!-- .line-content -->
</div>
