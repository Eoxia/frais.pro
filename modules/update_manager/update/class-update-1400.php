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
class Update_1400 {
	/**
	 * Limite de mise à jour des éléments par requêtes.
	 *
	 * @var integer
	 */
	private $limit = 5;

	/**
	 * Post type of note before version 1.4.0
	 *
	 * @var string
	 */
	private $old_note_type = 'ndf';

	/**
	 * Link old meta name of note (before version 1.4.0) to new.
	 *
	 * @var array
	 */
	private $link_note_metas_name = array(
		'_ndf_tax_inclusive_amount' => 'tax_inclusive_amount',
		'_ndf_tax_amount'           => 'tax_amount',
	);

	/**
	 * Old meta name of the status about the note.
	 *
	 * @var string
	 */
	private $old_status = '_ndf_validation_status';

	/**
	 * Post type of line before version 1.4.0
	 *
	 * @var string
	 */
	private $old_line_type = 'ndfl';

	/**
	 * Link old meta name of line (before version 1.4.0) to new.
	 *
	 * @var array
	 */
	private $link_line_metas_name = array(
		'_ndfl_vehicule'             => 'vehicule',
		'_ndfl_distance'             => 'distance',
		'_ndfl_tax_inclusive_amount' => 'tax_inclusive_amount',
		'_ndfl_tax_amount'           => 'tax_amount',
	);

	/**
	 * Old line type taxonomy name.
	 *
	 * @var string
	 */
	private $old_line_type_taxonomy = '_type_note';

	/**
	 * Old capability name.
	 *
	 * @var string
	 */
	private $old_capability_name = 'ndf_view_all';

	/**
	 * Le constructeur
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_frais_pro_update_1400_update_note', array( $this, 'callback_frais_pro_update_1400_update_note' ) );
		add_action( 'wp_ajax_frais_pro_update_1400_update_line', array( $this, 'callback_frais_pro_update_1400_update_line' ) );
		add_action( 'wp_ajax_frais_pro_update_1400_update_user_capabilities', array( $this, 'callback_frais_pro_update_1400_update_user_capabilities' ) );
		add_action( 'wp_ajax_frais_pro_update_1400_update_attachment_guid_mime_type', array( $this, 'callback_frais_pro_update_1400_update_attachment_guid_mime_type' ) );
	}

	/**
	 * Change note type.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_frais_pro_update_1400_update_note() {
		\eoxia\LOG_Util::log( __( 'Start update 1400 update_note method.', 'frais-pro' ), 'frais-pro' );

		global $wpdb;

		$schema = Note_Class::g()->get_schema();

		$old_posts_id = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type=%s", $this->old_note_type ) );

		// translators: %s is list of old posts id.
		\eoxia\LOG_Util::log( sprintf( __( 'List of existing note to be upgrading to new type: %s ', 'frais-pro' ), implode( ',', $old_posts_id ) ), 'frais-pro' );

		if ( ! empty( $old_posts_id ) ) {
			foreach ( $old_posts_id as $post_id ) {
				set_post_type( $post_id, Note_Class::g()->get_type() );

				// Update metadata.
				if ( ! empty( $this->link_note_metas_name ) ) {
					foreach ( $this->link_note_metas_name as $old_meta_name => $link_meta_name ) {
						$new_meta_name = $schema[ $link_meta_name ]['field'];
						$current_value = get_post_meta( $post_id, $old_meta_name, true );
						update_post_meta( $post_id, $new_meta_name, $current_value );

						// translators: %1$s value of the old meta. %2$s Value name of old meta. %3$s Value name of new meta. %4$d Line ID.
						\eoxia\LOG_Util::log( sprintf( __( 'Transfert value %1$s old meta %2$s to new meta %3$s for the post %4$d', 'frais-pro' ), $current_value, $old_meta_name, $new_meta_name, $post_id ), 'frais-pro' );
					}
				}

				// Update status of the note.
				$taxonomy_name = get_post_meta( $post_id, $this->old_status, true );
				$term          = $this->get_status_by_old( $taxonomy_name );

				if ( null !== $term->term_id ) {
					$status = wp_set_post_terms( $post_id, $term->term_id, Note_Status_Class::g()->get_type() );

					if ( is_wp_error( $status ) ) {
						// translators: %s is list of old posts id.
						\eoxia\LOG_Util::log( sprintf( __( 'Error for set term to the post %1$d : %2$s', 'frais-pro' ), $post_id, wp_json_encode( $status ) ), 'frais-pro' );
					} else {
						// translators: %s is list of old posts id.
						\eoxia\LOG_Util::log( sprintf( __( 'Set the term #%1$s for the post %2$d with the status %3$s', 'frais-pro' ), wp_json_encode( $term ), $post_id, wp_json_encode( $status ) ), 'frais-pro' );
					}
				} else {
					// translators: %s is list of old posts id.
					\eoxia\LOG_Util::log( sprintf( __( 'No term found for the post %1$d', 'frais-pro' ), $post_id ), 'frais-pro' );
				}
			}
		}

		// translators: %s is list of old posts id.
		\eoxia\LOG_Util::log( sprintf( __( 'List of existing note updated to the new type: %s ', 'frais-pro' ), implode( ',', $old_posts_id ) ), 'frais-pro' );

		\eoxia\LOG_Util::log( __( 'End update 1400 update_note method.', 'frais-pro' ), 'frais-pro' );

		wp_send_json_success( array(
			'updateComplete'    => false,
			'done'              => true,
			'progression'       => '',
			'doneDescription'   => '',
			'doneElementNumber' => 0,
			'errors'            => null,
		) );
	}

	/**
	 * Change line type.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_frais_pro_update_1400_update_line() {
		\eoxia\LOG_Util::log( __( 'Start update 1400 update_line method.', 'frais-pro' ), 'frais-pro' );

		// Register old taxonomy for next request. Only for this method.
		register_taxonomy( $this->old_line_type_taxonomy, Line_Class::g()->get_type() );

		global $wpdb;

		$schema = Line_Class::g()->get_schema();

		$old_posts_id = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type=%s", $this->old_line_type ) );

		// translators: %s is list of old posts id.
		\eoxia\LOG_Util::log( sprintf( __( 'List of existing line to be upgrading to new type: %s ', 'frais-pro' ), implode( ',', $old_posts_id ) ), 'frais-pro' );

		if ( ! empty( $old_posts_id ) ) {
			foreach ( $old_posts_id as $post_id ) {
				set_post_type( $post_id, Line_Class::g()->get_type() );

				if ( ! empty( $this->link_line_metas_name ) ) {
					foreach ( $this->link_line_metas_name as $old_meta_name => $link_meta_name ) {
						$new_meta_name = $schema[ $link_meta_name ]['field'];
						$current_value = get_post_meta( $post_id, $old_meta_name, true );
						update_post_meta( $post_id, $new_meta_name, $current_value );

						// translators: %1$s value of the old meta. %2$s Value name of old meta. %3$s Value name of new meta. %4$d Line ID.
						\eoxia\LOG_Util::log( sprintf( __( 'Transfert value %1$s old meta %2$s to new meta %3$s for the post %4$d', 'frais-pro' ), $current_value, $old_meta_name, $new_meta_name, $post_id ), 'frais-pro' );

					}
				}

				// Update taxonomy.
				$taxonomies_slug = wp_get_object_terms( $post_id, $this->old_line_type_taxonomy, array(
					'fields' => 'slugs',
				) );

				if ( ! empty( $taxonomies_slug ) ) {
					$term = $this->get_line_type_by_old( $taxonomies_slug );

					if ( null !== $term->term_id ) {
						$status = wp_set_post_terms( $post_id, $term->term_id, Line_Type_Class::g()->get_type(), true );

						if ( is_wp_error( $status ) ) {
							// translators: %s is list of old posts id.
							\eoxia\LOG_Util::log( sprintf( __( 'Error for set term to the post %1$d : %2$s', 'frais-pro' ), $post_id, wp_json_encode( $status ) ), 'frais-pro' );
						} else {
							// translators: %s is list of old posts id.
							\eoxia\LOG_Util::log( sprintf( __( 'Set the term #%1$s for the post %2$d with the status %3$s', 'frais-pro' ), wp_json_encode( $term ), $post_id, wp_json_encode( $status ) ), 'frais-pro' );
						}
					} else {
						// translators: %s is list of old posts id.
						\eoxia\LOG_Util::log( sprintf( __( 'No term found for the line %1$d', 'frais-pro' ), $post_id ), 'frais-pro' );
					}
				} else {
					// translators: %s is list of old posts id.
					\eoxia\LOG_Util::log( sprintf( __( 'No term found for the line %1$d', 'frais-pro' ), $post_id ), 'frais-pro' );
				}
			}
		}

		// translators: %s is list of old posts id.
		\eoxia\LOG_Util::log( sprintf( __( 'List of existing line updated to the new type: %s ', 'frais-pro' ), implode( ',', $old_posts_id ) ), 'frais-pro' );
		\eoxia\LOG_Util::log( __( 'End update 1400 update_line method.', 'frais-pro' ), 'frais-pro' );

		wp_send_json_success( array(
			'updateComplete'    => false,
			'done'              => true,
			'progression'       => '',
			'doneDescription'   => '',
			'doneElementNumber' => 0,
			'errors'            => null,
		) );
	}

	/**
	 * Update the capability "ndf_view_all" to "frais_pro_view_all_user_sheets" for user get the old capability.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_frais_pro_update_1400_update_user_capabilities() {
		\eoxia\LOG_Util::log( __( 'Start update 1400 update_user_capabilities method.', 'frais-pro' ), 'frais-pro' );
		$new_capability_name = \eoxia\Config_Util::$init['frais-pro']->user->capability_view_all_user_sheets;

		$users            = get_users( array( 'role' => 'administrator' ) );
		$list_users_login = array();

		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				// Translators: 1. Current caps for the user 2. User login currently treated.
				\eoxia\LOG_Util::log( sprintf( __( 'Key in user role %1$s of user : %2$s', 'frais-pro' ), implode( ',', $user->caps ), $user->user_login ), 'frais-pro' );
				if ( array_key_exists( $this->old_capability_name, $user->caps ) ) {
					$list_users_login[] = $user->user_login;

					// Translators: 1. User login currently treated. 2. Current capability for the user.
					\eoxia\LOG_Util::log( sprintf( __( 'The user %1$s get the old capability: %2$s', 'frais-pro' ), $user->user_login, $this->old_capability_name ), 'frais-pro' );
					$user->add_cap( $new_capability_name );
					// Translators: 1. New capability name for the user. 2. User login currently treated.
					\eoxia\LOG_Util::log( sprintf( __( 'New capability: %1$s added to user %2$s', 'frais-pro' ), $new_capability_name, $user->user_login ), 'frais-pro' );
				}
			}
		}

		// translators: %s is the new capability name.
		\eoxia\LOG_Util::log( sprintf( __( 'List of existing users updated to the new role: %1$s => %2$s', 'frais-pro' ), $new_capability_name, implode( ',', $list_users_login ) ), 'frais-pro' );
		\eoxia\LOG_Util::log( __( 'End update 1400 update_user_capabilities method.', 'frais-pro' ), 'frais-pro' );

		wp_send_json_success( array(
			'updateComplete'    => false,
			'done'              => true,
			'progression'       => '',
			'doneDescription'   => '',
			'doneElementNumber' => 0,
			'errors'            => null,
		) );
	}

	/**
	 * Update the GUID and mime type of attachment from note.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_frais_pro_update_1400_update_attachment_guid_mime_type() {
		\eoxia\LOG_Util::log( __( 'Start update 1400 update_attachment_guid_mime_type.', 'frais-pro' ), 'frais-pro' );

		global $wpdb;

		// Ici, le type de la note a déjà changé dans l'action "update_note".
		$notes_id = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type=%s", Note_Class::g()->get_type() ) );

		if ( ! empty( $notes_id ) ) {
			foreach ( $notes_id as $note_id ) {
				$attachments_id = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type=%s AND post_parent=%d", 'attachment', $note_id ) );

				if ( ! empty( $attachments_id ) ) {
					$documents = Document_Class::g()->get( array(
						'post_type'      => 'attachment',
						'post__in'       => $attachments_id,
						'posts_per_page' => -1,
						'post_status'    => array( 'publish', 'inherit' ),
					) );

					if ( ! empty( $documents ) ) {
						foreach ( $documents as $document ) {
							$document_info = Document_Class::g()->check_file( $document );

							// Suppression de l'ancienne meta contenant le type mime du fichier.
							delete_post_meta( $document->data['id'], 'mime_type' );

							// Appliques le bon GUID et le bon mime type si le fichier existe.
							if ( $document_info['exists'] ) {
								$document->data['mime_type'] = $document_info['mime_type']['type'];
								$document->data['link']      = $document_info['link'];
								$document->data['slug']      = $document->data['title'];
								$document->data['type']      = Document_Class::g()->get_type();
								$document                    = Document_Class::g()->update( $document->data );

								// translators: 1. <link_to_path> 2. <mime_type> 3. <i>.
								\eoxia\LOG_Util::log( sprintf( __( 'Updated GUID %1$s, mime_type %2$s and slug = %3$s for the document %4$d', 'frais-pro' ), $document->data['link'], $document->data['mime_type'], $document->data['slug'], $document->data['id'] ), 'frais-pro' );
							}
						}
					}
				}
			}
		}

		\eoxia\LOG_Util::log( __( 'End update 1400 update_attachment_guid_mime_type.', 'frais-pro' ), 'frais-pro' );

		wp_send_json_success( array(
			'updateComplete'    => false,
			'done'              => true,
			'progression'       => '',
			'doneDescription'   => '',
			'doneElementNumber' => 0,
			'errors'            => null,
		) );
	}

	/**
	 * Get the status by the old name.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param  string $old_name The old status name.
	 * @return mixed            null if statuses is empty. null if term is wp_error. Or WP_Term_Object if success.
	 */
	public function get_status_by_old( $old_name ) {
		Note_Status_Class::g()->init_status_note();
		$statuses = Note_Status_Class::g()->status;

		if ( empty( $statuses ) ) {
			return null;
		}

		foreach ( $statuses as $status ) {
			if ( $status['old_slug'] === $old_name ) {
				// translators: %s is list of old posts id.
				$category_slug = sanitize_title( $status['name'] );

				// translators: Slug of the category.
				\eoxia\LOG_Util::log( sprintf( __( 'Search term %1$s by slug', 'frais-pro' ), $category_slug ), 'frais-pro' );
				$term = get_term_by( 'slug', $category_slug, Note_Status_Class::g()->get_type() );

				if ( is_wp_error( $term ) ) {
					return null;
				}

				// translators: a json encoded of the term.
				\eoxia\LOG_Util::log( sprintf( __( 'Founded term %1$s', 'frais-pro' ), wp_json_encode( $term ) ), 'frais-pro' );

				return $term;
			}
		}

		return null;
	}

	/**
	 * Get the type of line by the old name.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param  array $old_taxonomies_slug Table of old types line name.
	 * @return mixed                      null if statuses is empty. null if term is wp_error. Or WP_Term_Object if success.
	 */
	public function get_line_type_by_old( $old_taxonomies_slug ) {

		// The index 0 is obviously the Line type.
		$old_taxonomies_slug = $old_taxonomies_slug[0];

		// translators: Slug of the line type.
		\eoxia\LOG_Util::log( sprintf( __( 'Search term %1$s by slug', 'frais-pro' ), $old_taxonomies_slug ), 'frais-pro' );
		$new_line_type = get_term_by( 'slug', $old_taxonomies_slug, Line_Type_Class::g()->get_type() );

		if ( is_wp_error( $new_line_type ) ) {
			return null;
		}

		// translators: a json encoded of the term.
		\eoxia\LOG_Util::log( sprintf( __( 'Founded term %1$s', 'frais-pro' ), wp_json_encode( $new_line_type ) ), 'frais-pro' );
		return $new_line_type;
	}
}

new Update_1400();
