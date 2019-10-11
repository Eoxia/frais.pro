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

<div id="top-header" class="ui sticky">
				<div class="alignleft">
	        <div class="alignleft floated column"><h4 class="page-title"><?php esc_html_e( 'Professionnal fees sheets', 'frais-pro' ); ?></h4></div>

	        <div class="wpeo-button button-blue action-attribute button-size-small button-radius-2"
	            data-action="create_note"
	            data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_note' ) ); ?>" >
	          	<i class="button-icon fas fa-plus-circle"></i>
	          	<span><?php esc_html_e( 'Add' ); ?></span>
	        </div>
			</div>

				<ul class="alignright">
					<li>
						<div class="wpeo-button button-square-30 button-radius-2 wpeo-modal-event"
							data-action="fp_modal_profil"
							data-nonce="<?php echo wp_create_nonce( 'open_modal_profil' ); ?>"
							data-class="modal-profil"><i class="button-icon fas fa-user-cog"></i></div>
					</li>
				</ul>
  </div>
