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
		parent::__construct( $data );
	}
}
