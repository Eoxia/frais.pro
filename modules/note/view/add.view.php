<?php
/**
 * Frais.pro main view. Call dashboard or include update message.
 *
 * @package Frais.pro
 * @subpackage Notes_Templates
 *
 * @since 1.5.0
 */
namespace frais_pro;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>


<li class="wpeo-button button-blue action-attribute button-size-small button-radius-2"
	 data-action="create_note"
	 data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_note' ) ); ?>" >
	<i class="button-icon fas fa-plus-circle"></i>
	<span><?php esc_html_e( 'Add' ); ?></span>
</li>
