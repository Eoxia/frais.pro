<?php
/**
 * Classe gérant les filtres des status des notes.
 *
 * @author eoxia
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
 * Classe gérant les filtres des status des notes.
 */
class Note_Status_Filter {

	/**
	 * Ajoutes les filtres
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function __construct() {
		add_filter( 'fp_filter_note_status_list', array( $this, 'callback_before_dropdown_note_status' ), 10, 2 );
	}

	/**
	 * Ajoutes "All status" pour le screen 'search'.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param array $status_list Tableau de Note_Status_Model.
	 *
	 * array['class']          string Classe supplémentaire pour personnalisé le dropdown. (optional)
	 * array['current_screen'] string Page courante. (optional)
	 *
	 * @param  array $args        (Voir au dessus).
	 *
	 * @return array $status_list Tableau de Note_Status_Model
	 */
	public function callback_before_dropdown_note_status( $status_list, $args ) {
		if ( isset( $args['current_screen'] ) ) {
			if ( 'search' === $args['current_screen'] ) {
				$tmp_status_note        = Note_Status_Class::g()->get( array( 'schema' => true ), true );
				$tmp_status_note->name  = __( 'All status', 'frais-pro' );
				$tmp_status_note->color = '#000000';
				array_unshift( $status_list, $tmp_status_note );
			}
		}

		return $status_list;
	}
}


new Note_Status_Filter();
