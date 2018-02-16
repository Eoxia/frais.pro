<?php
/**
 * Fonctions helper pour les documents associés à une note de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017-2018 Eoxia
 * @package Frais.pro
 * @subpackage Documents
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fill missing datas (just information) in document after get
 *
 * @since 1.4.0
 * @version 1.4.0
 *
 * @param  Document_Model $data Current document informations.
 *
 * @return Document_Model       The document with new informations.
 */
function build_document_datas( $data ) {
	$check_file = Document_Class::g()->check_file( $data );

	$data['mime_type'] = $check_file['mime_type'];

	return $data;
}
