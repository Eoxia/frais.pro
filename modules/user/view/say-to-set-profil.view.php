<?php
/**
 * Display modal box indicating to the user there is an required update on datas before using application.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2015-2018 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="wpeo-modal modal-user-setting-missing modal-active" >
	<div class="modal-container">
		<div class="modal-header">
			<h2 class="title"><?php echo esc_html_e( 'Missing profil settings', 'frais-pro' ); ?></h2>
			<div class="modal-close"><i class="fal fa-times"></i></div>
		</div>
		<div class="modal-content">
			<p style="font-size: 1.4em; margin-top: 10px;"><?php esc_html_e( 'You have to set your profil to get full feature from Frais.pro application.', 'frais-pro' ); ?></p>
		</div>
		<div class="modal-footer">
			<a class="wpeo-button button-blue" href="<?php echo esc_url( get_edit_profile_url() ); ?>">
				<span><?php esc_html_e( 'Go to my profile', 'frais-pro' ); ?></span>
			</a>
		</div>
	</div>
</div>
