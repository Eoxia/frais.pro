<?php
/**
 * Actions pour les documents dans Frais.pro
 *
 * @author Eoxia <dev@eoxia.com>
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
 * Classe principales gérant les documents ODT et CSV.
 */
class Document_Action {

	/**
	 * Le constructeur
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'callback_admin_init' ) );
	}

	/**
	 * Permet d'intialiser les catégories des documents de frais.pro.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_admin_init() {
		Document_Class::g()->init_document_type();
	}

}

new Document_Action();
