<?php
/**
 * La vue principale pour les mises à jour.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<h1><?php esc_html_e( 'Update Manager', 'frais-pro' ); ?></h1>

<?php if ( ! empty( $waiting_updates ) ) : ?>
	<?php foreach ( $waiting_updates as $version => $data ) : ?>
		<input type="hidden" name="version_available[]" value="<?php echo esc_attr( $version ); ?>" />

		<?php foreach ( $data as $index => $def ) : ?>
			<input type="hidden" name="version[<?php echo esc_attr( $version ); ?>][action][]" value="<?php echo esc_attr( $def['action'] ); ?>" />
			<input type="hidden" name="version[<?php echo esc_attr( $version ); ?>][description][]" value="<?php echo esc_attr( $def['description'] ); ?>" />
		<?php endforeach; ?>
	<?php endforeach; ?>
<?php else : ?>
	<?php esc_html_e( 'Nothing to update', 'frais-pro' ); ?>
	<strong><a href="<?php menu_page_url( 'frais-pro', true ); ?>"><?php echo esc_html_e( 'Back to Frais.pro main page.', 'frais-pro' ); ?></a></strong>
<?php endif; ?>

<ul class="log"></ul>
