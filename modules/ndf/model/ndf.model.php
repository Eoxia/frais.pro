<?php
/**
 * Définition du modèle de ndf
 *
 * @author eoxia
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2017 Eoxia
 * @package ndf
 * @subpackage model
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Définition du modèle de group ndf
 */
class NDF_Model extends \eoxia\Post_Model {

	/**
	 * Le constructeur définis le schéma
	 *
	 * @param object $object L'objet courant.
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function __construct( $object ) {
		$this->model['category_name'] = array(
			'type'          => 'string',
			'meta_type' => 'single',
			'field'         => '_ndf_category_name',
			'bydefault' => 'Auto',
		);

		$this->model['vehicule'] = array(
			'type'          => 'string',
			'meta_type' => 'single',
			'field'         => '_ndf_vehicule',
		);

		$this->model['distance'] = array(
			'type'          => 'integer',
			'meta_type' => 'single',
			'field'         => '_ndf_distance',
		);

		$this->model['TaxInclusiveAmount'] = array(
			'type'          => 'float',
			'meta_type' => 'single',
			'field'         => '_ndf_tax_inclusive_amount',
		);

		$this->model['TaxableAmount'] = array(
			'type'          => 'float',
			'meta_type' => 'single',
			'field'         => '_ndf_taxable_amount',
		);

		$this->model['TaxAmount'] = array(
			'type'          => 'float',
			'meta_type' => 'single',
			'field'         => '_ndf_tax_amount',
		);

		$this->model['associated_document_id'] = array(
			'type'				=> 'array',
			'meta_type'	=> 'multiple',
			'child' => array(
				'image' => array(
					'type'				=> 'array',
					'meta_type'	=> 'multiple'
				),
			),
		);

		parent::__construct( $object );
	}
}
