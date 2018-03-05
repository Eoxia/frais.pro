<?php
/**
 * Classe gérant les NDF
 *
 * @author eoxia
 * @since 1.0.0
 * @version 1.1.0
 * @copyright 2017 Eoxia
 * @package Frais.Pro
 * @subpackage class
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les groupe NDF
 */
class User_Class extends \eoxia\User_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\frais_pro\User_Model';

	/**
	 * Slug de base pour la route dans l'api rest
	 *
	 * @var string
	 */
	protected $base = 'personne';

}

User_Class::g();
