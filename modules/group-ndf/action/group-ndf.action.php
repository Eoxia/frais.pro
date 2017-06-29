<?php
/**
 * Classe gérant les actions des groupes NDF
 *
 * @author eoxia
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2017 Eoxia
 * @package group-ndf
 * @subpackage action
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Classe gérant les groupe NDF
 */
class Group_NDF_Action {

	/**
	 * Le constructeur
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_export_note_de_frais', array( $this, 'callback_export_note_de_frais' ) );
	}

	/**
	 * Génère un document .odt avec les données qui vont bien
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_export_note_de_frais() {
		// check_ajax_referer( 'callback_export_note_de_frais' );

		$group_ndf = Group_NDF_Class::g()->get( array(
			'include' => array( $_POST['id'] ),
		), true );

		$ndfs = NDF_Class::g()->get( array(
			'post_parent' => $_POST['id'],
		) );

		$sheet_details = array(
			'ndf' => array(
				'type' => 'segment',
				'value' => array(
				),
			),
		);

		if ( ! empty( $ndfs ) ) {
			foreach ( $ndfs as $ndf ) {
				$sheet_details['ndf']['value'][] = array(
					'date' => $ndf->date,
					'libelle' => $ndf->title,
					'typedenote' => 'Trajet',
					'vehicule' => 'C2',
					'km' => $ndf->distance,
					'ttc' => $ndf->ttc,
					'tva' => $ndf->tx_tva,
					'tvarecup' => 'tva recup',
					'photo' => 'photo',
				);
			}
		}

		document_class::g()->create_document( $group_ndf, $sheet_details );

		wp_send_json_success( array(
			'namespace' => '',
			'module' => '',
			'callback_success' => '',
		) );
	}
}

new Group_NDF_Action();
