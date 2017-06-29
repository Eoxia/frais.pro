<?php
/**
 * Classe gérant les NDF
 *
 * @author eoxia
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2017 Eoxia
 * @package ndf
 * @subpackage class
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Classe gérant les groupe NDF
 */
class NDF_Class extends \eoxia\Post_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name   				= '\note_de_frais\NDF_Model';

	/**
	 * Le post type
	 *
	 * @var string
	 */
	protected $post_type    				= 'ndf';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key    					= '_ndf';

	/**
	 * La fonction appelée automatiquement avant la création de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $before_post_function = array();

	/**
	 * La fonction appelée automatiquement avant la modification de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $before_put_function = array();

	/**
	 * La fonction appelée automatiquement après la récupération de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $after_get_function = array();

	/**
	 * Le nom pour le resgister post type
	 *
	 * @var string
	 */
	protected $post_type_name = 'NDF';
}

NDF_Class::g();
