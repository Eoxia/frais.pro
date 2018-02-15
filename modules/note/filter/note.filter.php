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
		add_filter( 'fp_filter_note_item_informations', array( $this, 'callback_note_item_informations' ) );
		add_filter( 'fp_filter_note_item_actions', array( $this, 'callback_note_item_actions' ) );
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
		if ( $note->contains_unaffected ) {
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

		if ( $note->contains_unaffected ) {
			$view = 'item-actions-unaffected';
		}

		\eoxia\View_Util::exec( 'frais-pro', 'note', 'filter/' . $view, array( 'note' => $note ) );
	}
}

new Note_Filter();
