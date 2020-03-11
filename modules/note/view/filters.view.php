<?php
/**
 * Vue principale de l'application
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="note-filters wpeo-form form-light">
	<div class="form-element">
		<label class="form-field-container">
			<span class="form-field-icon-prev"><i class="fas fa-filter"></i></span>
			<select id="filters" name="filters" class="form-field">
				<?php foreach ( $filter_options as $key => $value ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $filter ); ?> ><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</div>
	<div class="wpeo-button button-blue action-input"
	     data-id="<?php echo esc_attr( $note->data['id'] ); ?>"
	     data-parent="note-filters"
	     data-action="fp_filter_note"
	     data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_filter_note' ) ); ?>">
		<?php esc_html_e( 'Filter', 'theepi' ); ?>
	</div>
</div>
