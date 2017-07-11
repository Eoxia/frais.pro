<?php
/**
 * Définition du modèle de note de frais.
 *
 * @author eoxia
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2017 Eoxia
 * @package ndf
 * @subpackage model
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Définition du modèle de note de frais.
 */
class NDF_Model extends \eoxia\Post_Model {
	public function __construct( $data ) {
		$this->model['validation_status'] = array(
			'type'          => 'string',
			'meta_type'     => 'single',
			'field'         => '_ndf_validation_status',
			'bydefault'     => 'En cours',
		);
		$this->model['tax_inclusive_amount'] = array(
			'type'          => 'float',
			'meta_type'     => 'single',
			'field'         => '_ndf_tax_inclusive_amount',
		);

		$this->model['tax_amount'] = array(
			'type'          => 'float',
			'meta_type'     => 'single',
			'field'         => '_ndf_tax_amount',
		);

		parent::__construct( $data );
	}
}
