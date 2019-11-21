<?php
/**
 * Définition du modèle des paiements.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2017-2019 Eoxia
 * @package Eoxia/NodeDeFrais
 */

namespace frais_pro;

defined( 'ABSPATH' ) || exit;

/**
 * Définition du modèle des paiements.
 */
class Payment_Model extends \eoxia\Comment_Model {

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

		$this->schema['payment_type'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => 'fp_payment_type',
		);

		$this->schema['payment_number'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => 'fp_payment_number',
		);

		$this->schema['payment_amount'] = array(
			'type'      => 'float',
			'meta_type' => 'single',
			'field'     => 'fp_payment_amount',
		);

		parent::__construct( $data, $req_method );
	}

}
