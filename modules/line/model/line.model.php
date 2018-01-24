<?php
/**
 * Définition du modèle de ligne de note de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 * @subpackage LigneDeFrais
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Définition du modèle de ligne de note de frais.
 */
class Line_Model extends \eoxia\Post_Model {

	/**
	 * Le constructeur définis le schéma.
	 *
	 * @param object $object L'objet courant.
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 */
	public function __construct( $data = null, $req_method = null ) {
		$this->schema['category_name'] = array(
			'type'          => 'string',
			'meta_type'     => 'single',
			'field'         => '_ndfl_category_name',
		);

		$this->schema['vehicule'] = array(
			'type'          => 'string',
			'meta_type'     => 'single',
			'field'         => '_ndfl_vehicule',
		);

		$this->schema['distance'] = array(
			'type'          => 'integer',
			'meta_type'     => 'single',
			'field'         => '_ndfl_distance',
		);

		$this->schema['tax_inclusive_amount'] = array(
			'type'          => 'float',
			'meta_type'     => 'single',
			'field'         => '_ndfl_tax_inclusive_amount',
		);

		$this->schema['tax_amount'] = array(
			'type'          => 'float',
			'meta_type'     => 'single',
			'field'         => '_ndfl_tax_amount',
		);

		$this->schema['taxonomy'] = array(
			'type' => 'array',
			'meta_type' => 'multiple',
			'child' => array(
				'_type_note' => array(
					'meta_type' => 'multiple',
					'array_type' => 'integer',
					'type' => 'array',
				),
			),
		);

		parent::__construct( $data, $req_method );
	}

}
