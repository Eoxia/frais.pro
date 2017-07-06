<?php
/**
 * Functions helper pour les groupes ndf
 *
 * @package Eoxia\Plugin
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Récupères tous les éléments nécessaires pour le fonctionnement d'un groupes ndf
 *
 * @param  Object $data L'objet.
 * @return Object L'objet avec tous les éléments ajoutés par cette méthode.
 */
function before_update_ndf( $data ) {
	if ( 'Trajet' === $data->category_name ) {
		$user = User_Class::g()->get( array(
			'include' => array( get_current_user_id() ),
		), true );
		$data->TaxInclusiveAmount = $data->distance * $user->prixkm;
		$data->TaxAmount = 0;
	} else {
		$data->distance = 0;
	}
	return $data;
}

function after_update_ndf( $data ) {
	$group = Group_NDF_Class::g()->get( array(
		'id' => $data->parent_id,
	), true );
	$compilated_tva = 0;
	$compilated_ttc = 0;
	foreach ( NDF_Class::g()->get( array(
		'post_parent' => $group->id,
	) ) as $ndf ) {
		$compilated_ttc += $ndf->TaxInclusiveAmount;
		$compilated_tva += $ndf->TaxAmount;
	}
	$group->ttc = $compilated_ttc;
	$group->tx_tva = $compilated_tva;
	Group_NDF_Class::g()->update( $group );
	return $data;
}
