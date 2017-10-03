<?php
/**
 * Affichage du toggle pour gérer les types de note.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.2.0
 * @version 1.2.0
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
	<span class="action" contenteditable="false">
		<span class="label"><a href="<?php echo esc_attr( admin_url( 'edit-tags.php?taxonomy=_type_note' ) ); ?>" target="_blank"><?php esc_html_e( 'Aucun type de note', 'note-de-frais' ); ?></a></span>
	</span>
	<?php
else :
	?>
	<input name="category_name" type="hidden" value="" />

	<span class="action" contenteditable="false">
		<span class="label"><?php echo esc_html_e( 'Types de note', 'note-de-frais' ); ?></span>
		<i class="icon ion-ios-arrow-down"></i>
	</span>
	<ul class="content">
		<?php
		if ( ! empty( $types_note ) ) :
			foreach ( $types_note as $type_note ) :
				?>
				<li class="item" data-slug="<?php echo esc_attr( $type_note->slug ); ?>"><?php echo esc_html( $type_note->name ); ?></li>
				<?php
			endforeach;
		endif;
		?>

	</ul>
<?php endif; ?>
