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
}

new Note_Filter();
