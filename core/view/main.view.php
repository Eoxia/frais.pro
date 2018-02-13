<?php
/**
 * Frais.pro main view. Call dashboard or include update message.
 *
 * @package Frais.pro
 * @subpackage Notes_Templates
 *
 * @since 1.0.0.
 * @version 1.4.0
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<h1>
	<?php esc_html_e( 'Professionnal fees sheets', 'frais-pro' ); ?>

	<div class="wpeo-button button-blue action-attribute button-size-small button-radius-2"
			data-action="create_note"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_note' ) ); ?>" >
		<i class="button-icon fas fa-plus-circle"></i>
		<span><?php esc_html_e( 'Add' ); ?></span>
	</div>
</h1>

<div class="wrap wpeo-wrap wrap-frais-pro" >
	<?php if ( ! empty( $waiting_updates ) && strpos( $_SERVER['REQUEST_URI'], 'admin.php' ) && ! strpos( $_SERVER['REQUEST_URI'], 'admin.php?page=' . \eoxia\Config_Util::$init['frais-pro']->update_page_url ) ) : ?>
		<?php \eoxia\View_Util::exec( 'frais-pro', 'update_manager', 'say-to-update' ); ?>
	<?php else : ?>
		<?php Note_Class::g()->display(); ?>
		<?php if ( empty( $user->prixkm ) ) : ?>
			<?php \eoxia\View_Util::exec( 'frais-pro', 'user', 'say-to-set-profil' ); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>
