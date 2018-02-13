<?php
/**
 * Handle search action.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle search action.
 */
class Search_Action {

	/**
	 * Instanciate actions for search.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_fp_search_users', array( $this, 'callback_search_users' ) );
		add_action( 'wp_ajax_fp_search_notes', array( $this, 'callback_search_notes' ) );
	}

	/**
	 * Search admin user in database and return it.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function callback_search_users() {
		check_ajax_referer( 'fp_search_users' );

		$s = ! empty( $_POST['s'] ) ? sanitize_text_field( $_POST['s'] ) : '';

		if ( empty( $s ) ) {
			wp_send_json_error();
		}

		$user_query = new \WP_User_Query( array(
			'role'           => 'administrator',
			'search'         => '*' . $s . '*',
			'search_columns' => array(
				'user_login',
				'user_nicename',
				'user_email',
			),
		) );

		$users = $user_query->results;

		ob_start();
		\eoxia\View_Util::exec( 'frais-pro', 'note', 'search/results', array(
			'users' => $users,
		) );
		wp_send_json_success( array(
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Search notes in database and return view.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function callback_search_notes() {
		check_ajax_referer( 'fp_search_notes' );

		ob_start();
		\eoxia\View_Util::exec( 'frais-pro', 'note', 'search/results', array(
			'users' => $users,
		) );
		wp_send_json_success( array(
			'view' => ob_get_clean(),
		) );
	}

}

new Search_Action();
