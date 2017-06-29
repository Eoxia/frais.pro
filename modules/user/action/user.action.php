<?php
/**
 * Classe gérant les actions des utilisateurs
 *
 * @author eoxia
 * @since 1.0.0.0
 * @version 1.0.0.0
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

	public function callback_edit_user_profile( $user  ) {
		$user = User_Class::g()->get( array(
			'include' => array( $user->ID ),
		), true );

		\eoxia\View_Util::exec( 'note-de-frais', 'user', 'main', array(
			'user' => $user,
		) );
	}

	public function callback_user_profile_edit( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		$_POST['id'] = $user_id;
		User_Class::g()->update( $_POST );
	}

}

new User_Action();
