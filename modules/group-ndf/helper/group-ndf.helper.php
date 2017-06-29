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
function get_full_group( $data ) {
	$data->ttc = 0;
	$data->tx_tva = 0;

	return $data;
}
