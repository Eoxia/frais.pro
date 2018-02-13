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
<?php Search_Class::g()->display(); ?>

<?php if ( empty( $user->prixkm ) ) : ?>
	<div class="notice error"><p><?php \eoxia\View_Util::exec( 'frais-pro', 'user', 'need-profil-settings' ); ?></p></div>
<?php endif; ?>

<?php if ( empty( $note_list ) ) : ?>
	<div class="notice notice-info" >
		<p><?php esc_html_e( 'Actually you do not have professionnal fees sheet', 'frais-pro' ); ?></p>
	</div>
<?php endif; ?>

<table class="wpeo-table list-note main" >
	<tbody>
	<?php
	if ( ! empty( $note_list ) ) :
		foreach ( $note_list as $note ) :
			\eoxia\View_Util::exec( 'frais-pro', 'note', 'item', array(
				'note'                 => $note,
				'note_status_taxonomy' => $note_status_taxonomy,
				'status_list'          => $status_list,
			) );
		endforeach;
	endif;
	?>
	</tbody>
</table>

<?php Line_Class::g()->display_orphelans(); ?>
