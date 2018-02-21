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
	 * @param object $data       L'objet courant.
	 * @param string $req_method La méthode HTTP actuelle.
	 *
	 * @since 1.0.0
	 * @version 1.4.0
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

		$this->schema['category_name'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => 'fp_line_category_name',
		);

		$this->schema['vehicule'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => 'fp_line_vehicule',
		);

		$this->schema['distance'] = array(
			'type'              => 'integer',
			'meta_type'         => 'single',
			'field'             => 'fp_line_distance',
			'default'           => 0,
			'special_treatment' => 'km_calculation',
		);

		$this->schema['tax_inclusive_amount'] = array(
			'type'      => 'float',
			'meta_type' => 'single',
			'field'     => 'fp_line_tax_inclusive_amount',
		);

		$this->schema['tax_amount'] = array(
			'type'      => 'float',
			'meta_type' => 'single',
			'field'     => 'fp_line_tax_amount',
		);

		$this->schema['taxonomy'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'child'     => array(
				Line_Type_Class::g()->get_type() => array(
					'type'       => 'array',
					'array_type' => 'integer',
				),
			),
		);

		parent::__construct( $data, $req_method );
	}

}
