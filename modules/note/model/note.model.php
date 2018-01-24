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
	 * @param array|object $data Les données à construire à partir du modèle.
	 */
	public function __construct( $data ) {
		$this->model['validation_status'] = array(
			'type'          => 'string',
			'meta_type'     => 'single',
			'field'         => '_ndf_validation_status',
			'bydefault'     => __( 'In progress', 'frais-pro' ),
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
