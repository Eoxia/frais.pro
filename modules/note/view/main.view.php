<?php
/**
 * Vue principale de l'application
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php if ( empty( $user->prixkm ) ) : ?>
	<div class="notice error"><p><?php \eoxia\View_Util::exec( 'frais-pro', 'user', 'need-profil-settings' ); ?></p></div>
<?php endif; ?>

<?php Search_Class::g()->display(); ?>

<?php Note_Class::g()->display_list(); ?>

<?php Line_Class::g()->display_orphelans(); ?>
