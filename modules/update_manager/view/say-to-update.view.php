<?php
/**
 * La vue affichant à l'utilisateur de mêttre à jour DigiRisk.
 *
 * @author Jimmy Latour <jimmy@Eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="popup popup-update-manager active no-close">
	<div class="container">
		<div class="header">
			<h2 class="title"><?php echo esc_html_e( 'Update required', 'frais-pro' ); ?></h2>
		</div>
		<div class="content">
			<p style="font-size: 1.4em; margin-bottom: 10px;"><?php esc_html_e( 'Need to update Task Manager data', 'frais-pro' ); ?></p>
			<p style="font-size: 1.4em;"><?php esc_html_e( 'Warning! Stop the update process can destroy your data.', 'frais-pro' ); ?></p>

			<p style="text-align: center; margin-top: 20px;">
				<a class="button blue" href="<?php echo esc_attr( admin_url( 'admin.php?page=frais-pro-update' ) ); ?>">
					<span><?php esc_html_e( 'Start update', 'frais-pro' ); ?></span>
				</a>
				<a class="back-update" href="<?php echo esc_attr( admin_url( 'index.php' ) ); ?>"><?php esc_html_e( 'Back', 'frais-pro' ); ?></a>
			</p>
		</div>
	</div>
</div>
