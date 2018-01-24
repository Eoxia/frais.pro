<?php
/**
 * Fonctions helper pour les lignes de note de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 * @subpackage LigneDeFrais
 */

namespace frais_pro;

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
	if ( ! empty( $data->distance ) ) {
		$user = User_Class::g()->get( array(
			'include' => array( get_current_user_id() ),
		), true );
		$data->tax_inclusive_amount = $data->distance * $user->prixkm;
		$data->tax_amount = 0;
	}

	wp_set_object_terms( $data->id, array(), Type_Note_Class::g()->get_type(), false );

	$data->tax_inclusive_amount = round( $data->tax_inclusive_amount, 2 );
	$data->tax_amount = round( $data->tax_amount, 2 );

	return $data;
}

/**
 * Récupères les term_id associés à la ligne de note de frais.
 *
 * @since 1.2.0
 * @version 1.2.0
 *
 * @param  Object $data L'objet NDFL.
 * @return Object       L'objet NDFL avec les terms id.
 */
function get_current_category( $data ) {
	$data->current_category = null;
	$data->taxonomy['_type_note'] = wp_get_object_terms( $data->id, Type_Note_Class::g()->get_type() );
	if ( ! empty( $data->taxonomy['_type_note'] ) && ! empty( $data->taxonomy['_type_note'][0] ) ) {
		$data->current_category = Type_Note_Class::g()->get( array( 'id' => $data->taxonomy['_type_note'][0]->term_id ), true );
	}
	return $data;
}

/**
 * Met à jour la note de frais parente.
 *
 * @param  Object $data L'objet.
 * @return Object       L'objet non modifié.
 */
function after_update_ndfl( $data ) {
	$ndf = Note_Class::g()->get( array(
		'id' => $data->parent_id,
	), true );
	$compilated_tax_amount = 0;
	$compilated_tax_inclusive_amount = 0;
	$ndfls = Line_Class::g()->get( array(
		'post_parent' => $ndf->id,
	) );
	foreach ( $ndfls as $ndfl ) {
		$compilated_tax_inclusive_amount += $ndfl->tax_inclusive_amount;
		$compilated_tax_amount += $ndfl->tax_amount;
	}
	$ndf->tax_inclusive_amount = $compilated_tax_inclusive_amount;
	$ndf->tax_amount = $compilated_tax_amount;
	Note_Class::g()->update( $ndf );
	return $data;
}
