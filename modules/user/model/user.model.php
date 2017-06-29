<?php
/**
 * Définition du modèle des utilisateurs
 *
 * @author eoxia
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2017 Eoxia
 * @package user
 * @subpackage model
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Définition du modèle de group ndf
 */
class User_Model extends \eoxia\User_Model {

	/**
	 * Le constructeur définis le schéma
	 *
	 * @param object $object L'objet courant.
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function __construct( $object ) {
		$this->model['marque'] = array(
			'type'			=> 'string',
			'meta_type' => 'single',
			'field' 		=> '_ndf_marque',
		);

		$this->model['chevaux'] = array(
			'type'			=> 'integer',
			'meta_type' => 'single',
			'field' 		=> '_ndf_chevaux',
		);

		$this->model['prixkm'] = array(
			'type'			=> 'float',
			'meta_type' => 'single',
			'field' 		=> '_ndf_prixkm',
		);
		parent::__construct( $object );
	}
}
