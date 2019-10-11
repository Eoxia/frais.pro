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
}

$note = Note_Class::g()->get( array( 'id' => $_GET['note'] ), true );
$note_is_closed = false;
?>

<div id="top-header" class="ui sticky">

		<div class="header">
			<a href="<?php echo esc_url( menu_page_url( \eoxia\Config_Util::$init['frais-pro']->slug, false ) ); ?>" class="close"><i class="icon fas fa-chevron-left"></i></a>
			<div class="title">
				<h2><?php echo esc_html( $note->data['title'] ); ?></h2>
				<div class="note-last-update" ><?php esc_html_e( 'Last update', 'frais-pro' ); ?> : <?php echo esc_html( $note->data['date_modified']['rendered']['date_human_readable'] ); ?></div>
			</div>

			<?php
			Note_Status_Class::g()->display( $note->data['current_status']->data['id'], array(
				'class' => $note_is_closed ? 'button-disable' : '',
			) );
			?>

			<span class="export toggle list" data-parent="toggle" data-target="content">
				<?php echo apply_filters( 'fp_filter_note_item_actions', $note ); // WPCS XSS ok. ?>
			</span>
		</div>
  </div>
