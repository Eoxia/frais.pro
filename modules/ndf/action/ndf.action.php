<?php
/**
 * Classe gérant les actions des notes de frais.
 *
 * @author eoxia
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2017 Eoxia
 * @package ndf
 * @subpackage action
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les notes de frais.
 */
class NDF_Action {

	/**
	 * Le constructeur
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_open_ndf', array( $this, 'callback_open_ndf' ) );
		add_action( 'wp_ajax_create_ndf', array( $this, 'callback_create_ndf' ) );
		add_action( 'wp_ajax_modify_ndf', array( $this, 'callback_modify_ndf' ) );
		add_action( 'wp_ajax_archive_ndf', array( $this, 'callback_archive_ndf' ) );
		add_action( 'wp_ajax_export_ndf', array( $this, 'callback_export_ndf' ) );
	}

	/**
	 * Action : Ouvrir une note de frais.
	 *
	 * @param  string $_wpnonce          Nonce 'open_ndf' for check.
	 * @return string $view              Json NDF_Class::display(ID).
	 * @return string $callback_success  Json callback noteDeFrais.NDF.openNdf().
	 */
	public function callback_open_ndf() {
		check_ajax_referer( 'open_ndf' );

		$ndf_id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : -1;
		$ndf_display_mode = isset( $_POST['display_mode'] ) ? sanitize_text_field( $_POST['display_mode'] ) : 'grid';

		ob_start();
		NDFL_Class::g()->display( $ndf_id, $ndf_display_mode );
		$response = ob_get_clean();

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'callback_success' => 'open',
			'view' => $response,
			'display_mode' => $ndf_display_mode,
		) );
	}

	/**
	 * Action : créer une note de frais.
	 *
	 * @param  string $_wpnonce          Nonce 'create_ndf' for check.
	 * @return string $view              Json NDF_Class::display(ID).
	 * @return string $main_view         Json NDF_Class::display().
	 * @return string $callback_success  Json callback noteDeFrais.NDF.openNdf().
	 */
	public function callback_create_ndf() {
		check_ajax_referer( 'create_ndf' );

		$user = User_Class::g()->get( array(
			'include' => get_current_user_id(),
		), true );

		$date = current_time( 'Y-m' );

		$identifier = get_user_meta( get_current_user_id(), 'ndf_' . $date . '_identifier', true );

		if ( empty( $identifier ) ) {
			$identifier = 001;
		} else {
			$identifier++;
		}

		if ( intval( strlen( $identifier ) ) === 1 ) {
			$identifier = '00' . $identifier;
		}

		if ( intval( strlen( $identifier ) ) === 2 ) {
			$identifier = '0' . $identifier;
		}

		$ndf = NDF_Class::g()->update( array(
			'post_title' => strtoupper( $user->login ) . '-' . $date . '-' . $identifier,
			'post_status' => 'publish',
		) );

		update_user_meta( get_current_user_id(), 'ndf_' . $date . '_identifier', $identifier );

		ob_start();
		NDFL_Class::g()->display( $ndf->id );
		$response = ob_get_clean();

		ob_start();
		NDF_Class::g()->display();
		$response_main_view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'noteDeFrais',
			'module'           => 'NDF',
			'callback_success' => 'open',
			'view'             => $response,
			'main_view'        => $response_main_view,
		) );
	}

	/**
	 * Action : modifier une note de frais.
	 *
	 * @param  string $_wpnonce          Nonce 'modify_ndf' for check.
	 * @param  string Mixed              @see NDF_Model.
	 * @return string $ndf               Json updated ndf.
	 * @return string $view              Json NDF_Class::display(ID).
	 * @return string $callback_success  Json callback noteDeFrais.NDF.refreshNDF().
	 */
	public function callback_modify_ndf() {
		check_ajax_referer( 'modify_ndf' );

		$ndf = NDF_Class::g()->update( $_POST );

		ob_start();
		NDFL_Class::g()->display( $ndf->id );
		$response = ob_get_clean();

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'callback_success' => 'refresh',
			'ndf' => $ndf,
			'view' => $response,
		) );
	}

	/**
	 * Action : archiver une note de frais.
	 *
	 * @param  string $_wpnonce          Nonce 'archive_ndf' for check.
	 * @param  string $id                ID de le note de frais.
	 * @return string $ndf             Json updated note de frais.
	 * @return string $callback_success  Json callback noteDeFrais.NDF.archived().
	 */
	public function callback_archive_ndf() {
		check_ajax_referer( 'archive_ndf' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$ndf = NDF_Class::g()->get( array(
			'post__in' => array( $id ),
		), true );

		$ndf->status = 'archive';

		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'callback_success' => 'archived',
			'ndf' => NDF_Class::g()->update( $ndf ),
		) );
	}

	/**
	 * Génère un document .odt avec les données qui vont bien.
	 *
	 * @param  string $_wpnonce          Nonce 'export_ndf' for check.
	 * @param  string $id                ID de le note de frais.
	 * @return string $link              Json link file.
	 * @return string $filename          Json filename.
	 * @return string $callback_success  Json callback noteDeFrais.NDF.exportedNoteDeFraisSuccess().
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_export_ndf() {
		check_ajax_referer( 'export_ndf' );
		$total_tax_inclusive_amount = 0;
		$total_tax_amount = 0;
		$ndf = NDF_Class::g()->get( array(
			'include' => array( $_POST['id'] ),
		), true );

		$ndfls = NDFL_Class::g()->get( array(
			'post_parent' => $_POST['id'],
		) );

		$user = User_Class::g()->get( array(
			'include' => array( $ndf->author_id ),
		), true );

		$sheet_details = array(
			'ndf' => array(
				'type' => 'segment',
				'value' => array(),
			),
			'ndf_medias' => array(
				'type' => 'segment',
				'value' => array(),
			),
		);

		$periode = explode( '-', $ndf->title );
		$periode = $periode[2] . '/' . $periode[1];

		if ( ! empty( $user->firstname ) && ! empty( $user->lastname ) ) {
			$sheet_details['utilisateur_prenom_nom'] = $user->firstname . ' ' . $user->lastname;
		}
		$sheet_details['utilisateur_email'] = $user->email;
		$sheet_details['periode'] = $periode;

		if ( empty( $sheet_details['utilisateur_prenom_nom'] ) ) {
			$sheet_details['utilisateur_prenom_nom'] = $user->login;
		}

		$sheet_details['status'] = $ndf->validation_status;
		$sheet_details['miseajour'] = $ndf->date_modified;

		if ( ! empty( $ndfls ) ) {
			foreach ( $ndfls as $ndfl ) {
				$picture = '-';
				if ( ! empty( $ndfl->thumbnail_id ) ) {
					$picture_definition = wp_get_attachment_image_src( $ndfl->thumbnail_id, 'full' );
					$picture_final_path = str_replace( '\\', '/', str_replace( site_url( '/' ), ABSPATH, $picture_definition[0] ) );
					if ( is_file( $picture_final_path ) ) {
						$picture = array(
							'type'		=> 'picture',
							'value'		=> $picture_final_path,
							'option'	=> array(
								'size'	=> 10,
							),
						);
					}

					$sheet_details['ndf_medias']['value'][] = array(
						'id_media' => $ndfl->thumbnail_id,
						'media' => $picture,
					);
				}

				$sheet_details['ndf']['value'][] = array(
					'date' => $ndfl->date,
					'libelle' => $ndfl->title,
					'km' => $ndfl->distance,
					'ttc' => $ndfl->tax_inclusive_amount . '€',
					'tva' => $ndfl->tax_amount . '€',
					'id_media_attached' => ! empty( $ndfl->thumbnail_id ) ? $ndfl->thumbnail_id : '',
					'attached_media' => $picture,
				);

				$total_tax_inclusive_amount += $ndfl->tax_inclusive_amount;
				$total_tax_amount += $ndfl->tax_amount;
			}
		}

		$sheet_details['totaltva'] = $total_tax_amount . '€';
		$sheet_details['totalttc'] = $total_tax_inclusive_amount . '€';
		$sheet_details['marque'] = $user->marque;
		$sheet_details['chevaux'] = $user->chevaux;
		$sheet_details['prixkm'] = $user->prixkm;

		$response = document_class::g()->create_document( $ndf, $sheet_details );
		wp_send_json_success( array(
			'namespace' => 'noteDeFrais',
			'module' => 'NDF',
			'link' => $response['link'],
			'filename' => $response['filename'],
			'callback_success' => 'exportedNoteDeFraisSuccess',
		) );
	}
}

new NDF_Action();
