<?php
/**
 * Définition du modèle des statuts des notes.
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
 * Définition du modèle de type note.
 */
class Note_Status_Model extends \eoxia\Term_Model {
	/**
	 * Le constructeur définis le schéma.
	 *
	 * @param object $data       L'objet courant.
	 * @param object $req_method La méthode actuellement utilisée.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function __construct( $data = null, $req_method = null ) {
		$this->schema['special_treatment'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => 'fp_note_status_special_treatment',
		);

		$this->schema['is_default'] = array(
			'type'      => 'boolean',
			'meta_type' => 'single',
			'field'     => 'fp_note_status_is_default',
		);

		$this->schema['color'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => 'fp_note_status_color',
		);

		parent::__construct( $data, $req_method );
	}

}
