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
			'type' 			=> 'string',
			'meta_type'	=> 'single',
			'field' 		=> '_ndf_category_name',
		);

		$this->model['distance'] = array(
			'type'			=> 'integer',
			'meta_type' => 'single',
			'field' 		=> '_ndf_distance',
		);

		$this->model['ttc'] = array(
			'type'			=> 'float',
			'meta_type' => 'single',
			'field'			=> '_ndf_ttc',
		);

		$this->model['ht'] = array(
			'type'			=> 'float',
			'meta_type' => 'single',
			'field'			=> '_ndf_ht',
		);

		$this->model['tx_tva'] = array(
			'type'			=> 'float',
			'meta_type'	=> 'single',
			'field' 		=> '_ndf_tx_tva',
		);

		parent::__construct( $object );
	}
}
