<?php
/**
 * Définition du modèle de type note.
 *
 * @author eoxia
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2017 Eoxia
 * @package NDF
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Définition du modèle de type note.
 */
class Type_Note_Model extends \eoxia\Term_Model {

	/**
	 * Le constructeur définis le schéma.
	 *
	 * @param object $object L'objet courant.
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 */
	public function __construct( $data = null, $req_method = null ) {
		$this->schema['category_id'] = array(
			'type'          => 'string',
			'meta_type'     => 'single',
			'field'         => '_type_note_category_id',
		);

		$this->schema['special_treatment'] = array(
			'type'          => 'string',
			'meta_type'     => 'single',
			'field'         => '_type_note_special_treatment',
		);

		parent::__construct( $data );
	}

}
