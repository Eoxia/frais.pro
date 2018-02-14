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
			<span class="pin-dot" style="background:<?php echo esc_html( $default_status->color ); ?>;"></span>
			<span class="pin-label"><?php echo esc_html( $default_status->name ); ?></span>
		</span>
		<i class="button-icon fas fa-caret-down"></i>
	</button>
	<ul class="dropdown-content">
		<?php foreach ( $status_list as $note_status ) : ?>
			<li data-id="<?php echo esc_attr( $note_status->id ); ?>" class="dropdown-item pin-status">
				<span class="pin-dot" style="background:<?php echo esc_html( $note_status->color ); ?>;"></span>
				<span class="pin-label"><?php echo esc_html( $note_status->name ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
