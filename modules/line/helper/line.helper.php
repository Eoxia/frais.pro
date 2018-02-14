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
function before_update_line( $data ) {
	$data['tax_inclusive_amount'] = 0;
	$data['tax_amount']           = 0;

	if ( ! empty( $data['distance'] ) ) {
		$user = User_Class::g()->get( array(
			'include' => array( get_current_user_id() ),
		), true );

		$data['tax_inclusive_amount'] = $data['distance'] * $user->prixkm;
		$data['tax_amount']           = 0;
	}

	$data['tax_inclusive_amount'] = round( $data['tax_inclusive_amount'], 2 );
	$data['tax_amount']           = round( $data['tax_amount'], 2 );

	return $data;
}

/**
 * Fill missing datas (just information) in line after get
 *
 * @since 1.2.0
 * @version 1.4.0
 *
 * @param  Line_Model $data Current line informations.
 *
 * @return Line_Model       The line with new informations.
 */
function build_line_datas( $data ) {
	$data->current_category = null;

	$data->taxonomy[ Line_Type_Class::g()->get_type() ] = wp_get_object_terms( $data->id, Line_Type_Class::g()->get_type() );
	if ( ! empty( $data->taxonomy[ Line_Type_Class::g()->get_type() ] ) && ! empty( $data->taxonomy[ Line_Type_Class::g()->get_type() ][0] ) ) {
		$data->current_category = Line_Type_Class::g()->get( array( 'id' => $data->taxonomy[ Line_Type_Class::g()->get_type() ][0]->term_id ), true );
	}

	$data->line_status = Line_CLass::g()->check_line_status( $data );

	return $data;
}

/**
 * Met à jour la note de frais parente.
 *
 * @param  Object $data L'objet.
 * @return Object       L'objet non modifié.
 */
function after_update_line( $data ) {
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
