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

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php
if ( empty( $types_note ) ) :
	?>
	<span class="action">
		<span class="label"><a href="<?php echo esc_attr( admin_url( 'edit-tags.php?taxonomy=_type_note' ) ); ?>" target="_blank"><?php esc_html_e( 'Create type notes', 'frais-pro' ); ?></a></span>
	</span>
	<?php
else : ?>
	<input name="taxonomy[<?php echo esc_attr( Type_Note_Class::g()->get_taxonomy() ); ?>][0]" type="hidden" value="<?php echo ( ! empty( $ndfl->id ) && ! empty( $ndfl_type_note_id ) ? esc_attr( $ndfl_type_note_id ) : '' ); ?>" />

	<span class="action" contenteditable="false">
		<span class="label"><?php echo esc_html( $selected_type_note_name ); ?></span>
		<i class="icon fa fa-angle-down"></i>
	</span>
	<ul class="content">
<?php if ( ! empty( $types_note ) ) : ?>
	<?php foreach ( $types_note as $type_note ) : ?>
		<li class="item" data-id="<?php echo esc_attr( $type_note->id ); ?>" data-special-treatment="<?php echo esc_attr( $type_note->special_treatment ); ?>" ><?php echo esc_html( $type_note->category_id . ' : ' . $type_note->name ); ?></li>
	<?php endforeach; ?>
<?php endif; ?>
	</ul>

<?php endif; ?>
