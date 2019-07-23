<?php
/**
 * Définition du modèle des ODT de note de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2017-2018 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Définition du modèle des documents de note de frais.
 */
class Document_Model extends \eoxia\ODT_Model {

	/**
	 * Add custom field for fees sheet into default model.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param array|object $data       Les données à construire à partir du modèle.
	 * @param string       $req_method La méthode HTTP actuellement utilisée.
	 */
	public function __construct( $data = null, $req_method = null ) {

		$this->schema['unique_key'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => 'fp_unique_key',
		);

		$this->schema['unique_identifier'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => 'fp_unique_identifier',
		);
		
		$this->schema['document_meta'] = array(
			'type'      => 'array',
			'meta_type' => 'single',
			'field'     => 'document_meta',
		);

		parent::__construct( $data, $req_method );
	}
}
