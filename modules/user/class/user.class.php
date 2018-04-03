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

	/**
	 * Définition des champs obligatoires pour l'utilisation de Frais.pro
	 *
	 * @var array
	 */
	protected $required_fields = array();

	/**
	 * Initialisation de la classe
	 *
	 * @return void
	 */
	protected function construct() {
		parent::construct();

		$this->required_fields = array(
			'prixkm'    => __( 'Price per KM', 'frais.pro' ),
			'firstname' => __( 'Firstname', 'frais.pro' ),
			'lastname'  => __( 'Lastname', 'frais.pro' ),
		);
	}

	/**
	 * Vérification des champs obligatoire pour pouvoir utiliser l'application Frais.pro
	 *
	 * @param array $user L'utilisateur actuellement connecté.
	 *
	 * @return boolean Permet de savoir si les champs obligatoires sont bien définis.
	 */
	public function check_required_fields( $user ) {
		$required_are_setted = true;

		if ( ! empty( $this->required_fields ) ) {
			foreach ( $this->required_fields as $field_key => $field_label ) {
				if ( empty( $user[ $field_key ] ) ) {
					$required_are_setted = false;
				}
			}
		}

		return $required_are_setted;
	}

	/**
	 * Affiche le contenu de la popup indiquant qu'il y manque une donnée dans le compte client.
	 *
	 * @return void
	 */
	public function display_update_modal_message() {
		\eoxia\View_Util::exec( 'frais-pro', 'user', 'say-to-set-profil', array(
			'required_fields' => $this->required_fields,
		) );
	}

}

User_Class::g();
