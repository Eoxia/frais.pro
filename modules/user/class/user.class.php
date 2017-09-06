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
class User_Class extends \eoxia\User_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name   				= '\note_de_frais\User_Model';

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
	 * Slug de base pour la route dans l'api rest
	 *
	 * @var string
	 */
	protected $base  = 'personne';

	/**
	 * Instanciation de la gestion des document imprimés / Instanciate printes document
	 */
 	public function construct() {
 		parent::construct();
 	}

}

User_Class::g();
