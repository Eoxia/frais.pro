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
<div class="wpeo-modal modal-update-manager modal-active modal-force-display" >
	<div class="modal-container">
		<div class="modal-header">
			<h2 class="title"><?php echo esc_html_e( 'An update of Frais.pro data is required', 'frais-pro' ); ?></h2>
		</div>
		<div class="modal-content">
			<p style="font-size: 1.4em; margin-top: 10px;"><?php esc_html_e( 'Warning! Stop the update process can destroy your data.', 'frais-pro' ); ?></p>
		</div>
		<div class="modal-footer">
			<a class="wpeo-button button-transparent back-update" href="<?php echo esc_attr( admin_url( 'index.php' ) ); ?>" ><span><?php esc_html_e( 'Back', 'frais-pro' ); ?></span></a>
			<a class="wpeo-button button-blue" href="<?php echo esc_attr( admin_url( 'admin.php?page=frais-pro-update' ) ); ?>">
				<span><?php esc_html_e( 'Start update', 'frais-pro' ); ?></span>
			</a>
		</div>
	</div>
</div>
