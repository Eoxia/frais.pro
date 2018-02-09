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
		'_ndfl_tax_inclusive_amount' => 'tax_inclusive_amount',
		'_ndfl_tax_amount'           => 'tax_amount',
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
	 * Le constructeur
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_frais_pro_update_1400_update_note', array( $this, 'callback_frais_pro_update_1400_update_note' ) );
		add_action( 'wp_ajax_frais_pro_update_1400_update_line_type', array( $this, 'callback_frais_pro_update_1400_update_line_type' ) );
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
				$term_id       = $this->get_status_by_old( $taxonomy_name );

				if ( null !== $term_id ) {
					$status = wp_set_object_terms( $post_id, $term_id, Note_Status_Class::g()->get_type() );

					if ( is_wp_error( $status ) ) {
						// translators: %s is list of old posts id.
						\eoxia\LOG_Util::log( sprintf( __( 'Error for set term to the post %1$d : %2$s', 'frais-pro' ), $post_id, wp_json_encode( $status ) ), 'frais-pro' );
					} else {
						// translators: %s is list of old posts id.
						\eoxia\LOG_Util::log( sprintf( __( 'Set the term #%1$d for the post %2$d ', 'frais-pro' ), $term_id, $post_id ), 'frais-pro' );
					}
				} else {
					// translators: %s is list of old posts id.
					\eoxia\LOG_Util::log( sprintf( __( 'No term found for the post %2$d ', 'frais-pro' ), $post_id ), 'frais-pro' );

				}
			}
		}

		// translators: %s is list of old posts id.
		\eoxia\LOG_Util::log( sprintf( __( 'List of existing note updated to the new type: %s ', 'frais-pro' ), implode( ',', $old_posts_id ) ), 'frais-pro' );

		wp_send_json_success( array(
			'done' => true,
			'args' => array(
				'more' => true,
			),
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
	public function callback_frais_pro_update_1400_update_line_type() {
		global $wpdb;

		$schema = Line_Class::g()->get_schema();

		$old_posts_id = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type=%s", $this->old_line_type ) );

		// translators: %s is list of old posts id.
		\eoxia\LOG_Util::log( sprintf( __( 'List of existing note to be upgrading to new type: %s ', 'frais-pro' ), implode( ',', $old_posts_id ) ), 'frais-pro' );

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
			}
		}

		// translators: %s is list of old posts id.
		\eoxia\LOG_Util::log( sprintf( __( 'List of existing note updated to the new type: %s ', 'frais-pro' ), implode( ',', $old_posts_id ) ), 'frais-pro' );

		wp_send_json_success( array(
			'done' => true,
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
		$statuses = Note_Status_Class::g()->status;

		if ( empty( $statuses ) ) {
			return null;
		}

		foreach ( $statuses as $status ) {
			if ( $status['old_slug'] === $old_name ) {
				// translators: %s is list of old posts id.
				$category_slug = sanitize_title( $status['name'] );

				\eoxia\LOG_Util::log( sprintf( __( 'Search term %1$s by slug', 'frais-pro' ), $category_slug ), 'frais-pro' );
				$term = get_term_by( 'slug', $category_slug, Note_Status_Class::g()->get_type() );

				if ( is_wp_error( $term ) ) {
					return null;
				}

				\eoxia\LOG_Util::log( sprintf( __( 'Founded term %1$s', 'frais-pro' ), wp_json_encode( $term ) ), 'frais-pro' );

				return $term;
			}
		}

		return null;
	}

}

new Update_140();
