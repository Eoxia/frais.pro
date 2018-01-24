<?php
/**
 * Classe gérant les types de note des notes de frais.
 *
 * @author eoxia
 * @since 1.2.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package NDF
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les actions des types de note des notes de frais.
 */
class Type_Note_Class extends \eoxia\Term_Class {

	/**
	 * Nom du modèle à utiliser
	 *
	 * @var string
	 */
	protected $model_name = '\frais_pro\Type_Note_Model';

	/**
	 * Nom de la meta stockant les donnée
	 *
	 * @var string
	 */
	protected $meta_key = '_type_note';

	/**
	 * Nom de la taxonomie par défaut
	 *
	 * @var string
	 */
	protected $taxonomy = '_type_note';

	/**
	 * Base de l'url pour la REST API.
	 *
	 * @var string
	 */
	protected $base = 'type_note';

	/**
	 * Appelle la vue pour afficher le toggle.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param integer $ndfl (optional) La définition complète de la ligne de frais . Null par défaut.
	 * @return void
	 */
	public function display( $ndfl = null ) {
		$types_note = self::g()->get( array(
			'taxonomy' => $this->taxonomy,
		) );

		$ndfl_type_note = null !== $ndfl && ! empty( $ndfl->taxonomy[ $this->get_taxonomy() ][0] ) && ! empty( $ndfl->taxonomy[ $this->get_taxonomy() ][0]->term_id ) ? $ndfl->taxonomy[ $this->get_taxonomy() ][0]->term_id : 0;

		$selected_type_note_name = __( 'Note types', 'frais-pro' );
		if ( ! empty( $types_note ) && ! empty( $ndfl_type_note ) ) {
			foreach ( $types_note as $element ) {
				if ( $element->id === (int) $ndfl_type_note ) {
					$selected_type_note_name = $element->category_id . ' : ' . $element->name;
					break;
				}
			}
		}

		\eoxia\View_Util::exec( 'frais-pro', 'type-note', 'main', array(
			'types_note' => $types_note,
			'ndfl' => $ndfl,
			'ndfl_type_note_id' => $ndfl_type_note,
			'selected_type_note_name' => $selected_type_note_name,
		) );
	}

}

Type_Note_Class::g();
