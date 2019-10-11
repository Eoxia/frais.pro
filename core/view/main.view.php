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

<div class="content-wrap">
	
	<?php \eoxia\View_Util::exec( 'frais-pro', 'core', 'main-header' ); ?>

	<div class="wrap wpeo-wrap wrap-frais-pro">
		<?php if ( ! empty( $waiting_updates ) && strpos( $_SERVER['REQUEST_URI'], 'admin.php' ) && ! strpos( $_SERVER['REQUEST_URI'], 'admin.php?page=' . \eoxia\Config_Util::$init['frais-pro']->update_page_url ) ) : ?>
			<?php \eoxia\Update_Manager_Class::g()->display_say_to_update( 'frais-pro', __( 'Need to update Frais.pro data', 'frais-pro' ) ); ?>
		<?php else : ?>
			<?php Note_Class::g()->display(); ?>
			<?php if ( ! User_Class::g()->check_required_fields( $user ) ) : ?>
				<?php User_Class::g()->display_update_modal_message(); ?>
			<?php endif; ?>
		<?php endif; ?>
	</div>

</div>
