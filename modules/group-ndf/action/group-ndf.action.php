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
		add_action( 'wp_ajax_create_group_ndf', array( $this, 'callback_create_group_ndf' ) );
		add_action( 'wp_ajax_export_note_de_frais', array( $this, 'callback_export_note_de_frais' ) );
	}

	public function callback_create_group_ndf() {
		check_ajax_referer( 'create_group_ndf' );

		$user = User_Class::g()->get( array(
			'user__in' => get_current_user_id(),
		), true );

		$date = current_time( 'Y-m' );

		$identifier = get_user_meta( get_current_user_id(), 'ndf_' . $date . '_identifier', true );

		if ( empty( $identifier ) ) {
			$identifier = 001;
		} else {
			$identifier++;
		}

		if ( strlen( $identifier ) == 1 ) {
			$identifier = '00' . $identifier;
		}

		if ( strlen( $identifier ) == 2 ) {
			$identifier = '0' . $identifier;
		}

		$group_ndf = Group_NDF_Class::g()->update( array(
			'post_title' => strtoupper( $user->login ) . '-' . $date . '-' . $identifier,
		) );

		update_user_meta( get_current_user_id(), 'ndf_' . $date . '_identifier', $identifier );

		ob_start();
		NDF_Class::g()->display( $group_ndf->id );
		$response = ob_get_clean();

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'callback_success' => 'openNdf',
			'view' => $response,
		) );
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

		$total_ttc = 0;
		$group_ndf = Group_NDF_Class::g()->get( array(
			'include' => array( $_POST['id'] ),
		), true );

		$ndfs = NDF_Class::g()->get( array(
			'post_parent' => $_POST['id'],
		) );

		$user = User_Class::g()->get( array(
			'user__in' => $group_ndf->author_id,
		), true );

		$sheet_details = array(
			'ndf' => array(
				'type' => 'segment',
				'value' => array(
				),
			),
		);

		$periode = explode( '-', $group_ndf->title );
		$periode = $periode[2] . '/' . $periode[1];

		if ( ! empty( $user->firstname ) && ! empty( $user->lastname ) ) {
			$sheet_details['utilisateur_prenom_nom'] = $user->firstname . ' ' . $user->lastname;
		}
		$sheet_details['utilisateur_email'] = $user->email;
		$sheet_details['periode'] = $periode;

		if ( empty( $sheet_details['utilisateur_prenom_nom'] ) ) {
			$sheet_details['utilisateur_prenom_nom'] = $user->login;
		}

		if ( ! empty( $ndfs ) ) {
			foreach ( $ndfs as $ndf ) {
				$sheet_details['ndf']['value'][] = array(
					'date' => $ndf->date,
					'libelle' => $ndf->title,
					'km' => $ndf->distance,
					'ttc' => $ndf->TaxInclusiveAmount . '€',
					'tva' => $ndf->TaxAmount . '€',
					'tvarecup' => 'Je n\'existe pas',
					'photo' => 'photo',
				);

				$total_ttc += $ndf->TaxInclusiveAmount;
			}
		}

		$sheet_details['totalttc'] = $total_ttc . '€';

		$response = document_class::g()->create_document( $group_ndf, $sheet_details );
		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'groupNDF',
			'link' => $response['link'],
			'filename' => $response['filename'],
			'callback_success' => 'exportedNoteDeFraisSuccess',
		) );
	}
}

new Group_NDF_Action();
