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
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => 'fp_line_distance',
			'default'   => 0,
		);

		$this->schema['tax_inclusive_amount'] = array(
			'type'      => 'float',
			'meta_type' => 'single',
			'field'     => 'fp_line_tax_inclusive_amount',
			'default'   => 0,
		);

		$this->schema['tax_amount'] = array(
			'type'      => 'float',
			'meta_type' => 'single',
			'field'     => 'fp_line_tax_amount',
			'default'   => 0,
		);

		$this->schema['taxonomy'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'child'     => array(
				Line_Type_Class::g()->get_type() => array(
					'meta_type'  => 'multiple',
					'array_type' => 'integer',
					'type'       => 'array',
				),
			),
		);

		parent::__construct( $data, $req_method );
	}

}
