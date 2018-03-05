<?php
/**
 * Manage filters for line.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017-2018 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage filters for line.
 */
class Line_Filter {

	/**
	 * Instanciate filters for frais.pro.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function __construct() {
		add_filter( 'fp_filter_line_item_before', array( $this, 'callback_fp_filter_line_item_before' ), 10, 2 );
		add_filter( 'fp_filter_line_item_action_before', array( $this, 'callback_fp_filter_line_item_action_before' ), 10, 2 );

		$current_type = Line_Class::g()->get_type();
		add_filter( "eo_model_{$current_type}_before_put", array( $this, 'before_update_line' ), 10, 2 );
		add_filter( "eo_model_{$current_type}_before_post", '\frais_pro\before_get_identifier', 10, 2 );
		add_filter( "eo_model_{$current_type}_before_post", array( $this, 'before_update_line' ), 10, 2 );
		add_filter( "eo_model_{$current_type}_after_get", array( $this, 'after_get_line' ), 10, 2 );
		add_filter( "eo_model_{$current_type}_after_put", array( $this, 'after_update_line' ), 10, 2 );
	}

	/**
	 * Filter callback allowing to display some content at the start of a line in a note.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param string     $content Current content before calling this filter.
	 * @param Line_Model $line    Current line definition.
	 *
	 * @return void
	 */
	public function callback_fp_filter_line_item_before( $content, $line ) {
		$contains_unaffected = get_post_meta( $line->data['parent_id'], 'fp_contains_unaffected', true );

		if ( $contains_unaffected ) {
			ob_start();
			\eoxia\View_Util::exec( 'frais-pro', 'line', 'filter/checkbox', array(
				'line' => $line,
			) );
			$content = ob_get_clean();
		}

		return $content;
	}

	/**
	 * Filter callback allowing to display some content at the top of actions on a line
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param string     $content Current content before calling this filter.
	 * @param Line_Model $line    Current line definition.
	 *
	 * @return void
	 */
	public function callback_fp_filter_line_item_action_before( $content, $line ) {

		$contains_unaffected = get_post_meta( $line->data['parent_id'], 'fp_contains_unaffected', true );

		if ( ! $contains_unaffected ) {
			ob_start();
			\eoxia\View_Util::exec( 'frais-pro', 'line', 'filter/dissociation', array(
				'line' => $line,
			) );
			$content = ob_get_clean();
		}

		return $content;
	}

	/**
	 * Récupères tous les éléments nécessaires pour le fonctionnement d'une ligne de note de frais.
	 *
	 * @param  Object $data L'objet.
	 * @return Object       L'objet avec tous les éléments ajoutés par cette méthode.
	 */
	public function before_update_line( $data ) {
		if ( ! empty( $data['distance'] ) ) {
			$user = User_Class::g()->get( array(
				'id' => get_current_user_id(),
			), true );

			$data['tax_inclusive_amount'] = $data['distance'] * $user->data['prixkm'];
			$data['tax_amount']           = 0;
		}

		if ( isset( $data['tax_inclusive_amount'] ) ) {
			$data['tax_inclusive_amount'] = round( $data['tax_inclusive_amount'], 2 );
		}
		if ( isset( $data['tax_amount'] ) ) {
			$data['tax_amount'] = round( $data['tax_amount'], 2 );
		}

		return $data;
	}

	/**
	 * Fill missing datas (just information) in line after get
	 *
	 * @since 1.2.0
	 * @version 1.4.0
	 *
	 * @param  Line_Model $object Current line informations.
	 *
	 * @return Line_Model         The line with new informations.
	 */
	public function after_get_line( $object ) {
		// Définition d'une entrée contenant le type de la ligne.
		$object->data['current_category'] = null;
		if ( ! empty( $object->data['taxonomy'][ Line_Type_Class::g()->get_type() ] ) && ! empty( end( $object->data['taxonomy'][ Line_Type_Class::g()->get_type() ] ) ) ) {
			$object->data['current_category'] = Line_Type_Class::g()->get( array( 'id' => end( $object->data['taxonomy'][ Line_Type_Class::g()->get_type() ] ) ), true );
		}

		// Ajout du statut de la ligne selon la définition des champs obligatoire pour une ligne et des données de la ligne.
		$object->data['line_status'] = Line_CLass::g()->check_line_status( $object );

		// Vérification que le libellé est défini, si il est vide alors il reprend la valeur par défaut ci-dessous.
		$object->data['title'] = ! empty( $object->data['title'] ) ? $object->data['title'] : __( 'Label', 'frais-pro' );

		return $object;
	}

	/**
	 * Met à jour la note de frais parente.
	 *
	 * @param  Object $object L'objet.
	 *
	 * @return Object         L'objet modifié.
	 */
	public function after_update_line( $object ) {
		$compilated_tax_amount           = 0;
		$compilated_tax_inclusive_amount = 0;

		$lines = Line_Class::g()->get( array(
			'post_parent' => $object->data['parent_id'],
		) );
		foreach ( $lines as $line ) {
			$compilated_tax_inclusive_amount += $line->data['tax_inclusive_amount'];
			$compilated_tax_amount           += $line->data['tax_amount'];
		}

		$note['id']                   = $object->data['parent_id'];
		$note['date_modified']        = current_time( 'mysql' );
		$note['tax_inclusive_amount'] = $compilated_tax_inclusive_amount;
		$note['tax_amount']           = $compilated_tax_amount;
		Note_Class::g()->update( $note, true );

		return $object;
	}

}

new Line_Filter();
