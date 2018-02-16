<?php
/**
 * Define the different actions for 1.4.0 version.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2015-2018 Eoxia
 * @package Frais.pro
 */

$datas = array(
	array(
		'action'      => 'frais_pro_update_1400_update_note',
		'description' => __( 'Update note type and categorie. Also update metadata.', 'frais-pro' ),
		'since'       => '1.4.0',
		'version'     => '1.4.0',
	),
	array(
		'action'      => 'frais_pro_update_1400_update_line',
		'description' => __( 'Update line type and categorie. Also update metadata', 'frais-pro' ),
		'since'       => '1.4.0',
		'version'     => '1.4.0',
	),
	array(
		'action'      => 'frais_pro_update_1400_update_user_capabilities',
		'description' => __( 'Update user capability "ndf_view_all" to "frais_pro_view_all_user_sheets"', 'frais-pro' ),
		'since'       => '1.4.0',
		'version'     => '1.4.0',
	),
	array(
		'action'      => 'frais_pro_update_1400_update_attachment_guid_mime_type',
		'description' => __( 'Update attachment GUID and mime type', 'frais-pro' ),
		'since'       => '1.4.0',
		'version'     => '1.4.0',
	),
);
