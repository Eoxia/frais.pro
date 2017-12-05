<?php
/**
 * Functions helper pour les notes de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Formate le nom de la note de frais automatiquement selon le template défini. AAAAMMXXX-LOGIN
 *
 * @param  Object $data L'objet.
 * @return Object L'objet avec tous les éléments ajoutés par cette méthode.
 */
function set_ndf_name( $data ) {
	$user = User_Class::g()->get( array(
		'include' => get_current_user_id(),
	), true );

	$date = current_time( 'Y-m' );

	$identifier = get_user_meta( get_current_user_id(), 'ndf_' . $date . '_identifier', true );
	if ( empty( $identifier ) ) {
		$identifier = 001;
	} else {
		$identifier++;
	}

	if ( intval( strlen( $identifier ) ) === 1 ) {
		$identifier = '00' . $identifier;
	}

	if ( intval( strlen( $identifier ) ) === 2 ) {
		$identifier = '0' . $identifier;
	}
	update_user_meta( get_current_user_id(), 'ndf_' . $date . '_identifier', $identifier );

	$data->title = str_replace( '-', '', $date ) . $identifier . '-' . strtoupper( $user->displayname );

	return $data;
}
