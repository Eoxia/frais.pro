<?php
/**
 * Classe gérant les actions des utilisateurs
 *
 * @author eoxia
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package user
 * @subpackage action
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Classe gérant les actions des utilisateurs
 */
class User_Action {

	/**
	 * Le cosntructeur
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function __construct() {
		add_action( 'show_user_profile', array( $this, 'callback_edit_user_profile' ) );
		add_action( 'edit_user_profile', array( $this, 'callback_edit_user_profile' ) );

		add_action( 'personal_options_update', array( $this, 'callback_user_profile_edit' ) );
		add_action( 'edit_user_profile_update', array( $this, 'callback_user_profile_edit' ) );
	}

	/**
	 * Ajoute les champs spécifiques à note de frais dans le compte utilisateur.
	 *
	 * @param  WP_User $user L'objet contenant la définition complète de l'utilisateur.
	 */
	public function callback_edit_user_profile( $user  ) {
		$user = User_Class::g()->get( array(
			'include' => array( $user->ID ),
		), true );

		\eoxia\View_Util::exec( 'frais-pro', 'user', 'main', array(
			'user' => $user,
		) );
	}

	/**
	 * Enregistre les informations spécifiques de Note de Frais
	 *
	 * @param  integer $user_id L'identifiant de l'utilisateur pour qui il faut sauvegarder les informations.
	 */
	public function callback_user_profile_edit( $user_id ) {
		check_admin_referer( 'update-user_' . $user_id );
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		$user = array( 'id' => $user_id );
		$user['marque'] = ! empty( $_POST ) && ! empty( $_POST['marque'] ) ? sanitize_text_field( $_POST['marque'] ) : '';
		$user['chevaux'] = ! empty( $_POST ) && ! empty( $_POST['chevaux'] ) && in_array( $_POST['chevaux'], \eoxia\Config_Util::$init['frais-pro']->chevaux, true ) ? sanitize_text_field( $_POST['chevaux'] ) : '';
		$user['prixkm'] = ! empty( $_POST ) && ! empty( $_POST['prixkm'] ) ? sanitize_text_field( str_replace( ',', '.', $_POST['prixkm'] ) ) : '';

		if ( get_current_user_id() !== $user_id || ( 1 === get_current_user_id() ) ) {
			$user['ndf_admin'] = ! empty( $_POST ) && ! empty( $_POST['ndf_admin'] ) ? sanitize_text_field( $_POST['ndf_admin'] ) : '';
		}

		$user_update = User_Class::g()->update( $user );
		// On affecte le droit de voir toutes les notes à l'utilisateur si la case est cochée.
		if ( ! empty( $user_update ) && ! is_wp_error( $user_update ) ) {
			$the_user = new \WP_User( $user_id );
			if ( true === $user_update->ndf_admin ) {
				$the_user->add_cap( 'ndf_view_all' );
			} else {
				$the_user->remove_cap( 'ndf_view_all' );
			}
		}
	}

}

new User_Action();
