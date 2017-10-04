<?php
/**
 * Classe gérant les types de note des notes de frais.
 *
 * @author eoxia
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2017 Eoxia
 * @package NDF
 */

namespace note_de_frais;

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
	protected $model_name = '\note_de_frais\Type_Note_Model';

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
	 * @param integer $ndfl_id (optional) L'ID de la ligne de notre de frais. Défaut 0.
	 * @return void
	 */
	public function display( $ndfl_id = 0 ) {
		$types_note = self::g()->get( array(
			'taxonomy' => $this->taxonomy,
		) );

		$ndfl = NDFL_Class::g()->get( array(
			'id' => $ndfl_id,
		), true );

		$ndfl_type_note_id = ! empty( $ndfl->taxonomy[ self::g()->get_taxonomy() ][0] ) ? esc_attr( $ndfl->taxonomy[ self::g()->get_taxonomy() ][0] ) : 0;

		$selected_type_note_name = __( 'Note types', 'note-de-frais' );

		if ( ! empty( $types_note ) ) {
			foreach ( $types_note as $element ) {
				if ( $element->id === (int) $ndfl_type_note_id ) {
					$selected_type_note_name = $element->name;
					break;
				}
			}
		}

		\eoxia\View_Util::exec( 'note-de-frais', 'type-note', 'main', array(
			'types_note' => $types_note,
			'ndfl' => $ndfl,
			'ndfl_type_note_id' => $ndfl_type_note_id,
			'selected_type_note_name' => $selected_type_note_name,
		) );
	}
}

Type_Note_Class::g();
