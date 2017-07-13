<?php
/**
 * Définition du modèle de ligne de note de frais.
 *
 * @author eoxia
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2017 Eoxia
 * @package ndfl
 * @subpackage model
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
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function __construct( $object ) {
		$this->model['category_name'] = array(
			'type'          => 'string',
			'meta_type'     => 'single',
			'field'         => '_ndfl_category_name',
			'bydefault'     => 'Autre',
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

		$this->model['associated_document_id'] = array(
			'type'              => 'array',
			'meta_type'         => 'multiple',
			'child' => array(
				'image' => array(
					'type'      => 'array',
					'meta_type' => 'multiple',
				),
			),
		);

		parent::__construct( $object );
	}
}
