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
		\eoxia\View_Util::exec( 'frais-pro', 'search', 'results-users', array(
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

		$end_date           = ! empty( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : '';
		$start_date         = ! empty( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : '';
		$selected_status_id = ! empty( $_POST['selected_status_id'] ) ? (int) $_POST['selected_status_id'] : 0;
		$selected_user_id   = ! empty( $_POST['selected_user_id'] ) ? (int) $_POST['selected_user_id'] : 0;
		$include_archives   = ! empty( $_POST['include_archives'] ) ? (bool) $_POST['include_archives'] : false;

		$args = array();

		$args['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key'     => 'fp_contains_unaffected',
				'value'   => true,
				'compare' => '!=',
			),
			array(
				'key'     => 'fp_contains_unaffected',
				'compare' => 'NOT EXISTS',
			),
		);

		$args['date_query'] = array(
			array(
				'inclusive' => true,
			),
		);

		if ( ! empty( $start_date ) ) {
			$args['date_query'][0]['after'] = $start_date;
		}

		if ( ! empty( $end_date ) ) {
			$args['date_query'][0]['before'] = $end_date;
		}

		if ( ! empty( $selected_status_id ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => Note_Status_Class::g()->get_type(),
					'field'    => 'term_id',
					'terms'    => $selected_status_id,
				),
			);
		}

		if ( ! empty( $selected_user_id ) ) {
			$args['author'] = $selected_user_id;
		}

		if ( $include_archives ) {
			$args['post_status'] = array( 'publish', 'future', 'archive' );
		}

		ob_start();
		Note_Class::g()->display_list( $args );
		wp_send_json_success( array(
			'namespace'        => 'fraisPro',
			'module'           => 'search',
			'callback_success' => 'searchedSuccess',
			'view'             => ob_get_clean(),
		) );
	}

}

new Search_Action();
