<?php
/**
 * Définition du modèle des documents de note de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2017-2018 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Définition du modèle des documents de note de frais.
 */
class Document_Model extends \eoxia\Post_Model {

	/**
	 * Le constructeur définis le schéma.
	 *
	 * @param object $data       L'objet courant.
	 * @param string $req_method La méthode HTTP actuelle.
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 */
	public function __construct( $data = null, $req_method = null ) {
		$this->schema['mime_type'] = array(
			'type'    => 'string',
			'field'   => 'post_mime_type',
			'context' => array( 'GET' ),
		);

		$this->schema['_wp_attached_file'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => '_wp_attached_file',
		);

		$this->schema['model_path'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => 'fp_model_path',
		);

		$this->schema['document_meta'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
		);

		parent::__construct( $data, $req_method );
	}

}
