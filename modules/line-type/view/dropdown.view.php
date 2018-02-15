<?php
/**
 * Affichage du toggle pour gérer les types de note.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.2.0
 * @version 1.3.0
 * @copyright 2015-2017 Eoxia
 * @package NDF
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php if ( empty( $line_types ) ) : ?>
	<span class="action">
		<span class="label"><a href="<?php echo esc_attr( admin_url( 'edit-tags.php?taxonomy=_type_note' ) ); ?>" target="_blank"><?php esc_html_e( 'Create type notes', 'frais-pro' ); ?></a></span>
	</span>
<?php else : ?>
	<div class="wpeo-dropdown dropdown-large">
		<input name="type" type="hidden" value="<?php echo ( ! empty( $line->id ) && ! empty( $line_type_note_id ) ? esc_attr( $line_type_note_id ) : '' ); ?>" />
		<button class="dropdown-toggle wpeo-button button-grey<?php echo esc_attr( ! empty( $args ) && ! empty( $args['class'] ) ? ' ' . implode( ' ', $args['class'] ) : '' ); ?>" >
			<span><?php echo esc_html( $selected_type_note_name ); ?></span>
			<i class="button-icon fas fa-caret-down"></i>
		</button>
		<ul class="dropdown-content">
	<?php if ( ! empty( $line_types ) ) : ?>
		<?php foreach ( $line_types as $line_type ) : ?>
			<li class="dropdown-item" data-id="<?php echo esc_attr( $line_type->id ); ?>" data-special-treatment="<?php echo esc_attr( $line_type->special_treatment ); ?>" ><?php echo esc_html( $line_type->category_id . ' : ' . $line_type->name ); ?></li>
		<?php endforeach; ?>
	<?php endif; ?>
			</ul>
	</div>
<?php endif; ?>
