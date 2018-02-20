<?php
/**
 * Classe gérant les actions des notes de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.1
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les notes de frais.
 */
class Note_Action {

	/**
	 * Instanciate actions for frais.pro.
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_create_note', array( $this, 'callback_create_note' ) );
		add_action( 'wp_ajax_fp_note_archive', array( $this, 'callback_fp_note_archive' ) );
		add_action( 'wp_ajax_fp_update_note', array( $this, 'callback_fp_update_note' ) );

		add_action( 'wp_ajax_export_note', array( $this, 'callback_export_note' ) );

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );

		add_action( 'init', array( $this, 'register_note_status' ) );
	}

	/**
	 * Action : Create a note.
	 */
	public function callback_create_note() {
		check_ajax_referer( 'create_note' );
		$note_args = array(
			'status' => 'publish',
		);

		$default_status = Note_Status_Class::g()->get( array(
			'meta_query' => array(
				array(
					'key'   => 'fp_note_status_is_default',
					'value' => true,
				),
			),
		), true );

		if ( ! empty( $default_status ) && ! empty( $default_status->id ) ) {
			$note_args['$push']['taxonomy'] = array(
				Note_Status_Class::g()->get_type() => $default_status->id,
			);
		}
		$note = Note_Class::g()->create( $note_args );

		wp_send_json_success( array(
			'namespace'        => 'fraisPro',
			'module'           => 'note',
			'callback_success' => 'goToNote',
			'link'             => admin_url( 'admin.php?page=' . \eoxia\Config_Util::$init['frais-pro']->slug, false ) . '&note=' . $note->id,
		) );
	}

	/**
	 * Action : Archive a note.
	 */
	public function callback_fp_note_archive() {
		check_ajax_referer( 'fp_note_archive' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error( array( 'message' => __( 'You did not choose a note to mark as archive', 'frais-pro' ) ) );
		}

		$note = Note_Class::g()->get( array(
			'post__in' => array( $id ),
		), true );

		$note->status = 'archive';

		wp_send_json_success( array(
			'namespace'        => 'fraisPro',
			'module'           => 'note',
			'callback_success' => 'note_is_marked_as_archive',
			'note'             => Note_Class::g()->update( $note ),
			'link'             => admin_url( 'admin.php?page=' . \eoxia\Config_Util::$init['frais-pro']->slug, false ),
		) );
	}


	/**
	 * Action : modifier une note de frais.
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 */
	public function callback_fp_update_note() {
		check_ajax_referer( 'fp_update_note' );

		// Définition des arguments pour la mise à jour de la ligne.
		$note_args                  = array();
		$note_args['id']            = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$note_args['date_modified'] = current_time( 'mysql' );

		// Ajout de la catégorie de la ligne.
		$note_args['taxonomy'][ Note_Status_Class::g()->get_type() ] = isset( $_POST['selected_status_id'] ) ? intval( $_POST['selected_status_id'] ) : 0;

		// On lance la mise à jour de la note.
		$note = Note_Class::g()->update( $note_args, true );

		wp_send_json_success( array(
			'namespace'        => 'fraisPro',
			'module'           => 'note',
			'callback_success' => 'noteUpdated',
			'note'             => $note,
			'status'           => $note[ Note_Status_Class::g()->get_type() ],
			'link'             => admin_url( 'admin.php?page=' . \eoxia\Config_Util::$init['frais-pro']->slug, false ),
		) );
	}


	/**
	 * Génère un document .odt avec les données qui vont bien.
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 */
	public function callback_export_note() {
		check_ajax_referer( 'export_note' );

		$note_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$picture = ! empty( $_POST['picture'] ) ? (bool) $_POST['picture'] : false;
		$type    = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

		if ( empty( $type ) ) {
			$type = 'odt';
		}

		if ( empty( $note_id ) ) {
			wp_send_json_error();
		}

		$category = 'note';

		if ( $picture ) {
			$category = 'note-photo';
		}

		$response = Note_Class::g()->generate_document( $note_id, $category, $type );
		Document_Class::g()->generate_file( $response['document'], $type );

		$response['document'] = Document_Class::g()->get( array( 'id' => $response['document']['id'] ), true );

		ob_start();
		Document_Class::g()->display_item( $response['document'] );
		wp_send_json_success( array(
			'namespace'        => 'fraisPro',
			'module'           => 'note',
			'filename'         => $response['filename'],
			'view'             => ob_get_clean(),
			'callback_success' => 'exportedfraisProSuccess',
		) );
	}

	/**
	 * Register specific routes for NDF
	 *
	 * @since 1.3.0
	 * @version 1.3.1
	 */
	public function register_routes() {
		register_rest_route( __NAMESPACE__ . '/v' . \eoxia\Config_Util::$init['eo-framework']->wpeo_model->api_version , '/' . Note_Class::g()->get_rest_base() . '/(?P<id>[\d]+)/details', array(
			array(
				'method' => \WP_REST_Server::READABLE,
				'callback'	=> function( $request ) {
					$full_note = Note_Class::g()->get( array( 'id' => $request['id'] ), true );

					$full_note->children = Line_Class::g()->get( array( 'post_parent' => $request['id'] ) );

					return $full_note;
				},
				'permission_callback' => function() {
					if ( ( ! in_array( $_SERVER['REMOTE_ADDR'], \eoxia\Config_Util::$init['eo-framework']->wpeo_model->allowed_ip_for_unauthentified_access_rest, true ) ) && ! Note_Class::g()->check_cap( 'get' ) ) {
						return false;
					}
					return true;
				},
			),
		), true );
	}

	/**
	 * Register a custom post status for managing notes archives
	 *
	 * @return void
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function register_note_status() {
		register_post_status( 'archive', array(
			'label'                     => __( 'Archive', 'frais-pro' ),
			'internal'                  => true,
			'public'                    => true,
			'private'                   => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Unread <span class="count">(%s)</span>', 'Unread <span class="count">(%s)</span>', 'frais-pro' ),
		) );
	}

}

new Note_Action();
