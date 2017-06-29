<?php
/**
 * Vue principale de l'application
 *
 * @package Eoxia\Plugin
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<h2>
	<?php esc_html_e( 'Note de frais', 'note-de-frais' ); ?>
	<a href="#" class="action-attribute add-new-h2" data-action="create_task" data-nonce="66c8857434"><?php esc_html_e( 'Nouveau', 'note-de-frais' ); ?></a>
</h2>

<?php Group_NDF_Class::g()->display();
