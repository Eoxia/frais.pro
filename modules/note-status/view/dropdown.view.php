<?php
/**
 * Vue principale du dropdown.
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

<div class="validation_status wpeo-dropdown">
	<input type="hidden" name="selected_status_id" />

	<button class="dropdown-toggle wpeo-button button-main <?php echo esc_attr( $args['class'] ); ?>">
		<span class="pin-status">
			<span class="pin-dot" style="background:<?php echo esc_html( $default_status->data['color'] ); ?>;"></span>
			<span class="pin-label"><?php echo esc_html( $default_status->data['name'] ); ?></span>
		</span>
		<i class="button-icon fas fa-caret-down"></i>
	</button>
	<ul class="dropdown-content" data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_update_note' ) ); ?>" >
		<?php foreach ( $status_list as $key => $note_status ) : ?>
			<?php
			if ( 1 === $key && 'search' !== $args['current_screen'] ) :
				?>
				<li data-action="load_modal_payment" data-id="<?php echo esc_attr( $note->data['id'] ); ?>"
					data-title="<?php esc_html_e( 'Enter payment', 'frais-pro' ); ?>"
					class="dropdown-item pin-status wpeo-modal-event" data-special-treatment="<?php echo esc_attr( $note_status->data['special_treatment'] ); ?>" >
					<span class="pin-dot" style="background:<?php echo esc_html( $note_status->data['color'] ); ?>;"></span>
					<span class="pin-label"><?php echo esc_html( $note_status->data['name'] ); ?></span>
				</li>
				<?php
			else :
				?>
				<li data-id="<?php echo esc_attr( $note_status->data['id'] ); ?>" class="dropdown-item pin-status" data-special-treatment="<?php echo esc_attr( $note_status->data['special_treatment'] ); ?>" >
					<span class="pin-dot" style="background:<?php echo esc_html( $note_status->data['color'] ); ?>;"></span>
					<span class="pin-label"><?php echo esc_html( $note_status->data['name'] ); ?></span>
				</li>
				<?php
			endif;
			?>

		<?php endforeach; ?>
	</ul>
</div>
