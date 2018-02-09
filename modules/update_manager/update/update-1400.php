<?php
/**
 * Mise à jour des données pour la version 1.4.0
 *
 * @author Eoxia
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2015-2018 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mise à jour des données pour la version 1.4.0
 */
class Update_140 {
	/**
	 * Limite de mise à jour des éléments par requêtes.
	 *
	 * @var integer
	 */
	private $limit = 5;

	/**
	 * Le constructeur
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_frais_pro_update_1400_change_statuses_storage', array( $this, 'callback_frais_pro_update_1400_change_statuses_storage' ) );
	}

	/**
	 * Change notes statuses. Create a term into note-status taxonomy in order to have easyiest management on notes statuses.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_frais_pro_update_1400_change_statuses_storage() {
		global $wpdb;

		// Get existing statuses in order to create them into taxonomy.
		$existing_statuses = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT( meta_value ) FROM {$wpdb->postmeta} INNER JOIN {$wpdb->posts} ON ID = post_id WHERE meta_key = %s", '_ndf_validation_status' ) );
		foreach ( $existing_statuses as $status ) {

		}

		wp_send_json_success( array(
			'done' => true,
		) );
	}

}

new Update_140();
