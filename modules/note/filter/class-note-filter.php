<?php
/**
 * Classe gérant les filtres des notes de frais.
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
 * Classe gérant les filtres des notes de frais.
 */
class Note_Filter {

	/**
	 * Ajoutes les fitres liées au note.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function __construct() {
		add_filter( 'fp_filter_note_item_title', array( $this, 'callback_note_item_title' ), 10, 2 );
		add_filter( 'fp_filter_note_item_informations', array( $this, 'callback_note_item_informations' ) );
		add_filter( 'fp_filter_note_item_actions', array( $this, 'callback_note_item_actions' ) );

		$current_type = Note_Class::g()->get_type();
		add_filter( "eo_model_{$current_type}_before_post", '\frais_pro\before_post_identifier', 10, 2 );
		add_filter( "eo_model_{$current_type}_before_post", array( $this, 'set_note_name' ), 10, 2 );
		add_filter( "eo_model_{$current_type}_after_get", array( $this, 'get_full_note' ), 10, 2 );
		add_filter( "eo_model_{$current_type}_after_put", array( $this, 'get_full_note' ), 10, 2 );
	}

	/**
	 * Modifie le titre de la note
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param string     $title Le titre de la note.
	 * @param Note_Model $note   Les données de la note.
	 *
	 * @return string Titre modifié de la note.
	 */
	public function callback_note_item_title( $title, $note ) {
		if ( ! $note->data['contains_unaffected'] ) {
			return $title;
		}

		ob_start();
		\eoxia\View_Util::exec( 'frais-pro', 'note', 'filter/item-title', array( 'note' => $note ) );
		$title .= ob_get_clean();

		return $title;
	}

	/**
	 * Ajoutes des informations supplémentaires sur la ligne de la note à l'affichage.
	 *
	 * Si la note contient des lignes désaffectées, cette méthode n'appelle aucune vue.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param Note_Model $note         Les données de la note.
	 *
	 * @return void
	 */
	public function callback_note_item_informations( $note ) {
		if ( $note->data['contains_unaffected'] ) {
			return;
		}

		\eoxia\View_Util::exec( 'frais-pro', 'note', 'filter/item-informations', array( 'note' => $note ) );
	}

	/**
	 * Ajoutes des actions supplémentaires sur la ligne de la note à l'affichage.
	 *
	 * Si la note contient des lignes désaffectées, cette méthode appelle la vue item-actions-unaffected
	 * Sinon cette méthode appelle la vue item-actions
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param Note_Model $note Les données de la note.
	 *
	 * @return void
	 */
	public function callback_note_item_actions( $note ) {
		$view = 'item-actions';

		if ( $note->data['contains_unaffected'] ) {
			$view = 'item-actions-unaffected';
		} else {
			if ( ! empty( $note->data['last_document'] ) ) {
				foreach ( $note->data['last_document'] as &$document ) {
					$document['file_informations'] = Document_Class::g()->check_file( $document );

					$document['tooltip'] = __( 'File not generated', 'frais-pro' );

					if ( $document['file_informations']['exists'] ) {
						$document['tooltip'] = 'Generated on ' . $document->data['date']['rendered']['date'];
					}
				}
			}
		}

		\eoxia\View_Util::exec( 'frais-pro', 'note', 'filter/' . $view, array( 'note' => $note ) );
	}

	/**
	 * Formate le nom de la note de frais automatiquement selon le template défini. AAAAMMXXX-LOGIN
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 *
	 * @param  Object $data L'objet.
	 * @return Object L'objet avec tous les éléments ajoutés par cette méthode.
	 */
	public function set_note_name( $data ) {
		if ( isset( $data['contains_unaffected'] ) && $data['contains_unaffected'] ) {
			return $data;
		}

		$user = User_Class::g()->get( array(
			'include' => get_current_user_id(),
		), true );

		$date = current_time( 'Y-m' );

		$identifier = get_user_meta( get_current_user_id(), 'ndf_' . $date . '_identifier', true );
		if ( empty( $identifier ) ) {
			$identifier = 001;
		} else {
			$identifier++;
		}

		if ( intval( strlen( $identifier ) ) === 1 ) {
			$identifier = '00' . $identifier;
		}

		if ( intval( strlen( $identifier ) ) === 2 ) {
			$identifier = '0' . $identifier;
		}
		update_user_meta( get_current_user_id(), 'ndf_' . $date . '_identifier', $identifier );

		$data['title'] = str_replace( '-', '', $date ) . $identifier . '-' . strtoupper( $user->data['displayname'] );

		return $data;
	}

	/**
	 * Récupères tous les éléments nécessaires pour le fonctionnement d'une note
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 *
	 * @param Note_Model $object L'objet.
	 * @param array      $args   Des paramètres complémentaire pour permettre d'agir sur l'élément.
	 *
	 * @return Note_Model L'objet avec tous les éléments ajoutés par cette méthode.
	 */
	public function get_full_note( $object, $args ) {
		$args_note_status = array( 'schema' => true );

		if ( ! empty( $object->data['id'] ) && ! empty( $object->data['taxonomy'][ Note_Status_Class::g()->get_type() ] ) ) {
			$args_note_status = array( 'id' => end( $object->data['taxonomy'][ Note_Status_Class::g()->get_type() ] ) );
		}
		// Récupères la catégorie du danger.
		$object->data['current_status'] = Note_Status_Class::g()->get( $args_note_status, true );

		// Récupères les documents générés.
		$args_doc = array(
			'posts_per_page' => 1,
			'post_parent'    => $object->data['id'],
			'tax_query'      => array(
				array(
					'taxonomy' => Document_Class::g()->get_attached_taxonomy(),
					'field'    => 'slug',
				),
			),
		);

		$object->data['last_document']               = array();
		$args_doc['tax_query'][0]['terms']           = 'note-photo';
		$object->data['last_document']['note-photo'] = Document_Class::g()->get( $args_doc, true );
		$args_doc['tax_query'][0]['terms']           = 'note';
		$object->data['last_document']['note']       = Document_Class::g()->get( $args_doc, true );
		$args_doc['tax_query'][0]['terms']           = 'note-csv';
		$object->data['last_document']['note-csv']   = Document_Class::g()->get( $args_doc, true );

		return $object;
	}

}

new Note_Filter();
