<?php
/**
 * Définition du modèle de note de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Définition du modèle de note de frais.
 */
class Note_Model extends \eoxia\Post_Model {

	/**
	 * Add custom field for fees sheet into default model.
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

		$this->schema['validation_status'] = array(
			'type'       => 'string',
			'meta_type'  => 'single',
			'field'      => 'fp_note_validation_status',
			'deprecated' => '< 1.4.0',
		);

		$this->schema['tax_inclusive_amount'] = array(
			'type'      => 'float',
			'meta_type' => 'single',
			'field'     => 'fp_note_tax_inclusive_amount',
		);

		$this->schema['tax_amount'] = array(
			'type'      => 'float',
			'meta_type' => 'single',
			'field'     => 'fp_note_tax_amount',
		);

		$this->schema['contains_unaffected'] = array(
			'type'      => 'boolean',
			'meta_type' => 'single',
			'field'     => 'fp_contains_unaffected',
			'default'   => false,
		);

		$this->schema['taxonomy'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'child'     => array(
				Note_Status_Class::g()->get_type() => array(
					'meta_type' => 'multiple',
					'type'      => 'integer',
				),
			),
		);

		parent::__construct( $data, $req_method );
	}

}
