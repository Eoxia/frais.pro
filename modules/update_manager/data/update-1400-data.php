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
		'action'       => 'frais_pro_update_1400_update_note',
		'title'        => __( 'Update note type and categorie. Also update metadata.', 'frais-pro' ),
		'description'  => __( 'Change the different name and key to fit to plugin name. `_fp_note`', 'frais-pro' ),
		'since'        => '1.4.0',
		'version'      => '1.4.0',
		'update_index' => '140a',
	),
	array(
		'action'       => 'frais_pro_update_1400_update_line',
		'title'        => __( 'Update line type and categorie. Also update metadata', 'frais-pro' ),
		'description'  => __( 'Change the different name and key to fit to plugin name. `_fp_line`', 'frais-pro' ),
		'since'        => '1.4.0',
		'version'      => '1.4.0',
		'update_index' => '140b',
	),
	array(
		'action'       => 'frais_pro_update_1400_update_user_capabilities',
		'title'        => __( 'Update user capability "ndf_view_all" to "frais_pro_view_all_user_sheets"', 'frais-pro' ),
		'description'  => __( 'Change the name of user capability to view all notes to fit to plugin name', 'frais-pro' ),
		'since'        => '1.4.0',
		'version'      => '1.4.0',
		'update_index' => '140c',
	),
	array(
		'action'       => 'frais_pro_update_1400_update_attachment_guid_mime_type',
		'title'        => __( 'Update attachment GUID and mime type', 'frais-pro' ),
		'description'  => __( 'Fix errors on old generated documents', 'frais-pro' ),
		'since'        => '1.4.0',
		'version'      => '1.4.0',
		'update_index' => '140d',
	),
);
