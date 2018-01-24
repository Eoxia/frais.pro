<?php
/**
 * Classe gérant les statuts des notes.
 *
 * @author eoxia
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les actions des types de note des notes de frais.
 */
class Note_Status_Class extends \eoxia\Term_Class {

	/**
	 * Nom du modèle à utiliser
	 *
	 * @var string
	 */
	protected $model_name = '\frais_pro\Note_Status_Model';

	/**
	 * Nom de la meta stockant les donnée
	 *
	 * @var string
	 */
	protected $meta_key = '_note_status';

	/**
	 * Nom de la taxonomie par défaut
	 *
	 * @var string
	 */
	protected $taxonomy = '_note_status';

	/**
	 * Base de l'url pour la REST API.
	 *
	 * @var string
	 */
	protected $base = 'note_status';

}
