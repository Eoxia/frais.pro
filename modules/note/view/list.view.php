<?php
/**
 * Vue principale de l'application
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
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
