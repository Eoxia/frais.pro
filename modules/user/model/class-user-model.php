<?php
/**
 * Définition du modèle des utilisateurs
 *
 * @author eoxia
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2017-2018 Eoxia
 * @package user
 * @subpackage model
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Définition du modèle de group ndf
 */
class User_Model extends \eoxia\User_Model {

	/**
	 * Le constructeur définis le schéma
	 *
	 * @param array  $data       Les données à construire.
	 * @param string $req_method La méthode HTTP actuellement utilisée.
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 */
	public function __construct( $data = null, $req_method = null ) {
		$this->schema['default_display_mode'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => '_ndf_default_display_mode',
			'default'   => 'grid',
		);

		$this->schema['marque'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => '_ndf_marque',
		);

		$this->schema['chevaux'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => '_ndf_chevaux',
		);

		$this->schema['prixkm'] = array(
			'type'      => 'float',
			'meta_type' => 'single',
			'field'     => '_ndf_prixkm',
		);

		$this->schema['ndf_admin'] = array(
			'type'      => 'boolean',
			'meta_type' => 'single',
			'field'     => '_ndf_user_is_admin',
		);

		// Ajout des champs image dans le compte utilisateur.
		$this->schema['thumbnail_id'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => '_ndf_thumbnail_id',
		);

		$this->schema['associated_document_id'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'child'     => array(
				'image' => array(
					'type'      => 'array',
					'meta_type' => 'multiple',
				),
			),
		);

		parent::__construct( $data, $req_method );
	}
}
