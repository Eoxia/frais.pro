<?php
/**
 * Classe gérant les notes de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les notes de frais.
 */
class Note_Class extends \eoxia\Post_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\frais_pro\Note_Model';

	/**
	 * Le post type
	 *
	 * @var string
	 */
	protected $post_type = 'fp_note';

	/**
	 * Slug de base pour la route dans l'api rest
	 *
	 * @var string
	 */
	protected $base = 'note';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key = 'fp_note';

	/**
	 * La fonction appelée automatiquement avant la création de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $before_post_function = array( '\frais_pro\set_note_name' );

	/**
	 * La fonction appelée automatiquement avant la modification de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $before_put_function = array();

	/**
	 * La fonction appelée automatiquement après la récupération de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $after_get_function = array( '\frais_pro\get_full_note' );

	/**
	 * Le nom pour le resgister post type
	 *
	 * @var string
	 */
	protected $post_type_name = 'Note';

	/**
	 * Récupères les notes de frais et les envoies à la vue principale.
	 *
	 * @param  array $status Post_status, permet d'afficher notes archivés ou publique.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 */
	public function display( $status = array( 'publish', 'future' ) ) {
		$note_status_taxonomy = Note_Status_Class::g()->get_type();
		$status_list          = Note_Status_Class::g()->get();

		$requested_note = ! empty( $_GET ) && isset( $_GET['note'] ) && ! empty( $_GET['note'] ) ? $_GET['note'] : '';

		// Display list of exsiting notes.
		if ( '' === $requested_note ) {
			$args_note_list = array(
				'post_status' => $status,
			);
			if ( ! current_user_can( 'frais_pro_view_all_user_sheets' ) ) {
				$args_note_list['author'] = get_current_user_id();
			}

			\eoxia\View_Util::exec( 'frais-pro', 'note', 'list', array(
				'note_list'            => $this->get( $args_note_list ),
				'note_status_taxonomy' => $note_status_taxonomy,
				'status_list'          => $status_list,
				'user'                 => User_Class::g()->get( array( 'user_id' => get_current_user_id() ), true ),
			) );
		} elseif ( is_int( (int) $requested_note ) && ( 0 !== (int) $requested_note ) ) { // Display a given note.
			$current_note = $this->get( array( 'id' => $requested_note ), true );

			\eoxia\View_Util::exec( 'frais-pro', 'note', 'main', array(
				'note_is_closed'       => ! empty( $current_note->$note_status_taxonomy->special_behaviour ) && ( 'closed' === $current_note->$note_status_taxonomy->special_behaviour ) ? true : false,
				'display_mode'         => 'grid',
				'note'                 => $current_note,
				'lines'                => Line_Class::g()->get( array( 'post_parent' => $requested_note ) ),
				'note_status_taxonomy' => $note_status_taxonomy,
				'status_list'          => $status_list,
			) );
		} elseif ( '' !== $requested_note && 'unclassified' === $requested_note ) {
			Line_Class::g()->display_orphelan_list();
		}
	}

	/**
	 * Génères le document.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  integer $ndf_id       L'ID de la note de frais.
	 * @param  boolean $with_picture Oui pour avoir les photos. Defaut true.
	 * @param  string  $type         Type de fichier a exporter.
	 *
	 * @return array {
	 *         Les propriétés du tableau.
	 *
	 *         @type string ....
	 * }.
	 */
	public function generate_document( $ndf_id, $with_picture = true, $type = 'odt' ) {
		$total_tax_inclusive_amount = 0;
		$total_tax_amount = 0;
		$ndf = self::g()->get( array(
			'id' => $ndf_id,
		), true );

		$ndfls = Line_Class::g()->get( array(
			'post_parent' => $ndf_id,
		) );

		$user = User_Class::g()->get( array(
			'include' => array( $ndf->author_id ),
		), true );

		$types_de_note = Line_Type_Class::g()->get();
		$list_type_de_note = array();
		foreach ( $types_de_note as $type_de_note ) {
			$list_type_de_note[ $type_de_note->id ]['id'] = $type_de_note->category_id;
			$list_type_de_note[ $type_de_note->id ]['name'] = $type_de_note->name;
		}

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

		$periode = substr( $ndf->title, 0, 4 ) . '/' . substr( $ndf->title, 4, 2 );

		if ( ! empty( $user->firstname ) && ! empty( $user->lastname ) ) {
			$sheet_details['utilisateur_prenom_nom'] = $user->firstname . ' ' . $user->lastname;
		}
		$sheet_details['utilisateur_email'] = $user->email;
		$sheet_details['periode'] = $periode;

		if ( empty( $sheet_details['utilisateur_prenom_nom'] ) ) {
			$sheet_details['utilisateur_prenom_nom'] = $user->login;
		}

		$sheet_details['status'] = $ndf->validation_status;
		$sheet_details['miseajour'] = $ndf->date_modified['rendered']['date_human_readable'];

		if ( ! empty( $ndfls ) ) {
			foreach ( $ndfls as $ndfl ) {
				if ( $with_picture ) {
					$picture = '-';
					if ( ! empty( $ndfl->thumbnail_id ) ) {
						$picture_definition = wp_get_attachment_image_src( $ndfl->thumbnail_id, 'large' );
						$picture_final_path = str_replace( '\\', '/', str_replace( site_url( '/' ), ABSPATH, $picture_definition[0] ) );
						if ( is_file( $picture_final_path ) ) {
							$picture = array(
								'type' => 'picture',
								'value' => $picture_final_path,
								'option' => array(
									'size' => 8,
								),
							);
						}

						$sheet_details['ndf_medias']['value'][] = array(
							'id_media' => $ndfl->thumbnail_id,
							'media' => $picture,
						);
					}
				}

				$categorie_id = '-';
				$categorie_label = $ndfl->category_name;
				if ( ! empty( $ndfl->taxonomy[ Line_Type_Class::g()->get_type() ] ) && ! empty( $ndfl->taxonomy[ Line_Type_Class::g()->get_type() ][0] ) && array_key_exists( $ndfl->taxonomy[ Line_Type_Class::g()->get_type() ][0]->term_id, $list_type_de_note ) ) {
					$categorie_id = $list_type_de_note[ $ndfl->taxonomy[ Line_Type_Class::g()->get_type() ][0]->term_id ]['id'];
					$categorie_label = $list_type_de_note[ $ndfl->taxonomy[ Line_Type_Class::g()->get_type() ][0]->term_id ]['name'];
				}

				$sheet_details['ndf']['value'][] = array(
					'id_ligne' => $ndfl->id,
					'date' => $ndfl->date['date_input']['fr_FR']['date_time'],
					'libelle' => $ndfl->title,
					'num_categorie' => $categorie_id,
					'nom_categorie' => $categorie_label,
					'km' => $ndfl->distance,
					'ttc' => $ndfl->tax_inclusive_amount . '€',
					'tva' => $ndfl->tax_amount . '€',
					'id_media_attached' => ! empty( $ndfl->thumbnail_id ) ? $ndfl->thumbnail_id : '',
					'attached_media' => $with_picture ? $picture : '',
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

		$response = Document_Class::g()->create_document( $ndf, $sheet_details, $with_picture, $type );

		return $response;
	}

}

Note_Class::g();
