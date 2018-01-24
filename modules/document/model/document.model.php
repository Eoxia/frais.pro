<?php namespace frais_pro;

if ( !defined( 'ABSPATH' ) ) exit;

class document_model extends \eoxia\Post_Model {

	/**
	 * Construit le modèle / Fill the model
	 *
	 * @param array|WP_Object $object La définition de l'objet dans l'instance actuelle / Object currently present into model instance
	 * @param string $meta_key Le nom de la metakey utilisée pour le rangement des données associées à l'élément / The main metakey used to store data associated to current object
	 * @param boolean $cropped Permet de ne récupèrer que les données principales de l'objet demandé / If true, return only main informations about object
	 */
	public function __construct( $data = null, $req_method = null ) {
			$this->schema['mime_type'] = array(
			'type' 				=> 'string',
			'meta_type'		=> 'single',
			'field'				=> 'post_mime_type'
		);

		parent::__construct( $data, $req_method );
	}

}
