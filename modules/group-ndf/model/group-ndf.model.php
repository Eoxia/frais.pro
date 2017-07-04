<?php
/**
 * Définition du modèle de group ndf
 *
 * @author eoxia
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2017 Eoxia
 * @package group-ndf
 * @subpackage model
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Définition du modèle de group ndf
 */
class Group_NDF_Model extends \eoxia\Post_Model {
	public function __construct( $data ) {
		$this->model['ttc'] = array(
			'type'          => 'float',
			'meta_type' => 'single',
			'field'         => '_group_ndf_ttc_amount',
		);

		$this->model['tx_tva'] = array(
			'type'          => 'float',
			'meta_type' => 'single',
			'field'         => '_group_ndf_tva_amount',
		);

		parent::__construct( $data );
	}
}
