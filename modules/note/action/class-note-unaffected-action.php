<?php
/**
 * Classe gérant les actions des notes avec des lignes désaffectées.
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
 * Classe gérant les actions des notes avec des lignes désaffectées.
 */
class Note_Unaffected_Action {

	/**
	 * Instantie les actions.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_fp_search_notes_reassign', array( $this, 'callback_search_notes_reassign' ) );
		add_action( 'wp_ajax_reassign_lines', array( $this, 'callback_reassign_lines' ) );
	}

	/**
	 * Recherches les notes en base de donnée, puis les renvoies au formulaire autocomplete.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_search_notes_reassign() {
		check_ajax_referer( 'search_notes_reassign' );

		$s = ! empty( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';

		if ( empty( $s ) ) {
			wp_send_json_error();
		}

		$post_query = new \WP_Query( array(
			'fields'      => 'ids',
			'post_type'   => Note_Class::g()->get_type(),
			's'           => $s,
			'post_status' => array( 'publish', 'future' ),
			'meta_query'  => array(
				'relation' => 'OR',
				array(
					'key'     => 'fp_contains_unaffected',
					'value'   => false,
					'compare' => '=',
				),
				array(
					'key'     => 'fp_contains_unaffected',
					'compare' => 'NOT EXISTS',
				),
			),
		) );

		$note_ids = $post_query->posts;

		$notes = array();

		if ( ! empty( $note_ids ) ) {
			$notes = Note_Class::g()->get( array(
				'post__in' => $note_ids,
			) );
		}

		ob_start();
		\eoxia\View_Util::exec( 'frais-pro', 'search', 'results-notes', array(
			'notes' => $notes,
		) );
		wp_send_json_success( array(
			'view' => ob_get_clean(),
		) );
	}


	/**
	 * Met à jour le parent_id pour chaque ligne reçu par le formulaire.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_reassign_lines() {
		check_ajax_referer( 'reassign_lines' );

		$parent_id       = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$current_note_id = ! empty( $_POST['current_note_id'] ) ? (int) $_POST['current_note_id'] : 0;

		if ( empty( $parent_id ) || empty( $current_note_id ) ) {
			wp_send_json_error();
		}

		$current_note = Note_Class::g()->get( array( 'id' => $current_note_id ), true );

		$lines_id         = ! empty( $_POST['lines_id'] ) ? (array) $_POST['lines_id'] : array();
		$updated_lines_id = array();

		if ( ! empty( $lines_id ) ) {
			foreach ( $lines_id as $line_id ) {
				$line_id = (int) $line_id;

				if ( $line_id ) {
					$line = Line_Class::g()->update( array(
						'id'        => $line_id,
						'parent_id' => $parent_id,
					) );

					if ( empty( $line->wp_errors ) ) {
						$current_note->data['count_line']--;
						$updated_lines_id[] = $line->data['id'];
					}
				}
			}

			Note_Class::g()->update( $current_note->data );
		}

		wp_send_json_success( array(
			'namespace'        => 'fraisPro',
			'module'           => 'noteUnaffected',
			'callback_success' => 'reassignedLineUnaffectedSuccess',
			'updated_lines_id' => $updated_lines_id,
		) );
	}
}

new Note_Unaffected_Action();
