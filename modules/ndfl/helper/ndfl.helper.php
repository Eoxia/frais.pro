<?php
/**
 * Fonctions helper pour les lignes de note de frais.
 *
 * @package Eoxia\Plugin
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Récupères tous les éléments nécessaires pour le fonctionnement d'une ligne de note de frais.
 *
 * @param  Object $data L'objet.
 * @return Object       L'objet avec tous les éléments ajoutés par cette méthode.
 */
function before_update_ndfl( $data ) {
	if ( 'Trajet' === $data->category_name ) {
		$user = User_Class::g()->get( array(
			'include' => array( get_current_user_id() ),
		), true );
		$data->tax_inclusive_amount = $data->distance * $user->prixkm;
		$data->tax_amount = 0;
	} else {
		$data->distance = 0;
	}
	$data->tax_inclusive_amount = round( $data->tax_inclusive_amount, 2 );
	$data->tax_amount = round( $data->tax_amount, 2 );
	return $data;
}

/**
 * Met à jour la note de frais parente.
 * @param  Object $data L'objet.
 * @return Object       L'objet non modifié.
 */
function after_update_ndfl( $data ) {
	$ndf = NDF_Class::g()->get( array(
		'id' => $data->parent_id,
	), true );
	$compilated_tax_amount = 0;
	$compilated_tax_inclusive_amount = 0;
	$ndfls = NDFL_Class::g()->get( array(
		'post_parent' => $ndf->id,
	) );
	foreach ( $ndfls as $ndfl ) {
		$compilated_tax_inclusive_amount += $ndfl->tax_inclusive_amount;
		$compilated_tax_amount += $ndfl->tax_amount;
	}
	$ndf->tax_inclusive_amount = $compilated_tax_inclusive_amount;
	$ndf->tax_amount = $compilated_tax_amount;
	NDF_Class::g()->update( $ndf );
	return $data;
}
