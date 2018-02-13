<?php
/**
 * Frais.pro main view. Call dashboard or include update message.
 *
 * @package Frais.pro
 * @subpackage Notes_Templates
 *
 * @since 1.4.0
 * @version 1.4.0
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<h1>
	<?php esc_html_e( 'Professionnal fees sheets', 'frais-pro' ); ?>
</h1>

<div class="wrap wpeo-wrap wrap-frais-pro" >
	<?php if ( ! empty( $waiting_updates ) && strpos( $_SERVER['REQUEST_URI'], 'admin.php' ) && ! strpos( $_SERVER['REQUEST_URI'], 'admin.php?page=' . \eoxia\Config_Util::$init['frais-pro']->update_page_url ) ) : ?>
		<?php \eoxia\View_Util::exec( 'frais-pro', 'update_manager', 'say-to-update' ); ?>
	<?php else : ?>
		<?php Note_Class::g()->display_single(); ?>
		<?php if ( empty( $user->prixkm ) ) : ?>
			<?php \eoxia\View_Util::exec( 'frais-pro', 'user', 'say-to-set-profil' ); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>
