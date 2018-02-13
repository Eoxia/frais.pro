<?php
/**
 * Results of the users search.
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

if ( ! empty( $users ) ) :
	foreach ( $users as $user ) :
		?>
		<li class="autocomplete-result">
			<?php echo get_avatar( $user->id, 32, '', '', array( 'class' => 'autocomplete-result-image' ) ); ?>
			<div class="autocomplete-result-container">
				<span class="autocomplete-result-title"><?php echo esc_html( $user->display_name ); ?></span>
				<span class="autocomplete-result-subtitle"><?php echo esc_html( $user->user_email ); ?></span>
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
