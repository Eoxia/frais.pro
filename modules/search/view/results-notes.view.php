<?php
/**
 * Results of the note search.
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
}

if ( ! empty( $notes ) ) :
	foreach ( $notes as $note ) :
		?>
		<li data-id="<?php echo esc_attr( $note->data['id'] ); ?>" data-result="<?php echo esc_html( apply_filters( 'fp_filter_note_item_title', $note->data['title'], $note ) ); ?>" class="autocomplete-result-text">
			<div class="autocomplete-result-container">
				<span class="autocomplete-result-title"><?php echo esc_html( apply_filters( 'fp_filter_note_item_title', $note->data['title'], $note ) ); ?></span>
			</div>
		</li>
		<?php
	endforeach;
else :
	?>
	<li class="autocomplete-result-text">
		<div class="autocomplete-result-container">
			<span class="autocomplete-result-title">Pas de résultats</span>
			<span class="autocomplete-result-subtitle">Essayez en changeant les mots clés</span>
		</div>
	</li>
	<?php
endif;
