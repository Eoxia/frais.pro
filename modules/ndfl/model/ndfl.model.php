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

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Définition du modèle de ligne de note de frais.
 */
class NDFL_Model extends \eoxia\Post_Model {

	/**
	 * Le constructeur définis le schéma.
	 *
	 * @param object $object L'objet courant.
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 */
	public function __construct( $object ) {
		$this->model['category_name'] = array(
			'type'          => 'string',
			'meta_type'     => 'single',
			'field'         => '_ndfl_category_name',
		);

		$this->model['vehicule'] = array(
			'type'          => 'string',
			'meta_type'     => 'single',
			'field'         => '_ndfl_vehicule',
		);

		$this->model['distance'] = array(
			'type'          => 'integer',
			'meta_type'     => 'single',
			'field'         => '_ndfl_distance',
		);

		$this->model['tax_inclusive_amount'] = array(
			'type'          => 'float',
			'meta_type'     => 'single',
			'field'         => '_ndfl_tax_inclusive_amount',
		);

		$this->model['tax_amount'] = array(
			'type'          => 'float',
			'meta_type'     => 'single',
			'field'         => '_ndfl_tax_amount',
		);

		$this->model['taxonomy'] = array(
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

		parent::__construct( $object );
	}

}
