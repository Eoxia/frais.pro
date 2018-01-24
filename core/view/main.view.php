<?php
/**
 * Vue principale de l'application
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
<div class="wrap wpeo-wrap wrap-frais-pro" >
	<?php if ( ! empty( $waiting_updates ) && strpos( $_SERVER['REQUEST_URI'], 'admin.php' ) && ! strpos( $_SERVER['REQUEST_URI'], 'admin.php?page=frais-pro-update' ) ) : ?>
		<?php \eoxia\View_Util::exec( 'frais-pro', 'update_manager', 'say-to-update' ); ?>
	<?php else : ?>
		<?php Note_Class::g()->display(); ?>
	<?php endif; ?>
<div>
