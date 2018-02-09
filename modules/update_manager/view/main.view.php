<?php
/**
 * Display update manager main page.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2015-2018 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<h1><?php esc_html_e( 'Update Manager', 'frais-pro' ); ?></h1>
<?php if ( ! empty( $waiting_updates ) ) : ?>
	<div class="grid-layout padding w2" >
		<?php foreach ( $waiting_updates as $version => $data ) : ?>
			<input type="hidden" name="version_available[]" value="<?php echo esc_attr( $version ); ?>" />

			<?php foreach ( $data as $index => $def ) : ?>
			<div class="block">
				<div class="container">
					<h3><?php echo esc_html( sprintf( __( 'V%1$s - %2$s', 'frais-pro' ), $def['version'], $def['description'] ) ); ?></h3>
					<?php echo esc_attr( $def[ 'description_' . $def['version'] ] ); ?>
					<input type="hidden" name="version[<?php echo esc_attr( $version ); ?>][action][]" value="<?php echo esc_attr( $def['action'] ); ?>" />
					<input type="hidden" name="version[<?php echo esc_attr( $version ); ?>][description][]" value="<?php echo esc_attr( $def['description'] ); ?>" />
				</div>
			</div>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
<?php else : ?>
	<?php esc_html_e( 'Nothing to update', 'frais-pro' ); ?>
	<strong><a href="<?php menu_page_url( 'frais-pro', true ); ?>"><?php echo esc_html_e( 'Back to Frais.pro main page.', 'frais-pro' ); ?></a></strong>
<?php endif; ?>

<!-- Template definition to avoid hardcoded template in JS -->
<script type="text/html" id="tmpl-my-template">
	<span></span>
</script>
<ul class="log"></ul>
