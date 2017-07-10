<?php
/**
 * Functions helper pour les notes de frais.
 *
 * @package Eoxia\Plugin
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Récupères tous les éléments nécessaires pour le fonctionnement d'une note de frais.
 *
 * @param  Object $data L'objet.
 * @return Object L'objet avec tous les éléments ajoutés par cette méthode.
 */
function get_full_ndf( $data ) {
	/*
	$data->tax_inclusive_amount = 0;
	$data->tax_amount = 0;*/
	return $data;
}
