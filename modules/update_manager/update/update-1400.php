<?php
/**
 * Mise à jour des données pour la version 1.4.0
 *
 * @author Eoxia
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
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
	private $limit = 50;

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
	 * Récupères le nombre de points
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_frais_pro_update_1400_change_statuses_storage() {
		wp_send_json_success( array(
			'done' => true,
		) );
	}

}

new Update_140();
