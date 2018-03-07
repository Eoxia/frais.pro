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
		<li data-id="<?php echo esc_attr( $user->ID ); ?>" data-result="<?php echo esc_html( $user->display_name ); ?>" class="autocomplete-result">
			<?php echo get_avatar( $user->ID, 32, '', '', array( 'class' => 'autocomplete-result-image autocomplete-image-rounded' ) ); ?>
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
			<span class="autocomplete-result-title"><?php esc_html_e( 'No notes founded', 'frais-pro' ); ?></span>
			<span class="autocomplete-result-subtitle"><?php esc_html_e( 'Try again by changing keywords', 'frais-pro' ); ?></span>
		</div>
	</li>
	<?php
endif;
