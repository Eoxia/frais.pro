<?php
/**
 * Classe gérant les notes de frais.
 *
 * @author eoxia
 * @since 1.0.0
 * @version 1.2.0
 * @copyright 2017 Eoxia
 * @package NDF
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les notes de frais.
 */
class NDF_Class extends \eoxia\Post_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\note_de_frais\NDF_Model';

	/**
	 * Le post type
	 *
	 * @var string
	 */
	protected $post_type  = 'ndf';

	/**
	 * Slug de base pour la route dans l'api rest
	 *
	 * @var string
	 */
	protected $base  = 'note-de-frais';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key   = '_ndf';

	/**
	 * La fonction appelée automatiquement avant la création de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $before_post_function = array();

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
	protected $after_get_function = array( '\note_de_frais\get_full_ndf' );

	/**
	 * Le nom pour le resgister post type
	 *
	 * @var string
	 */
	protected $post_type_name = 'NDF';

	/**
	 * Nom du statut à afficher.
	 *
	 * @var array
	 */
	public $status = array(
		'En cours' => 'en-cours',
		'Validée'  => 'valide',
		'Payée'    => 'paye',
		'Refusée'  => 'refuse',
	);

	/**
	 * Le ou les statuts pour lesquels on ne peut plus modifier les notes
	 *
	 * @var array
	 *
	 * @todo nécessite un transfert
	 */
	public $closed_status = array(
		'Payée'
	);

	/**
	 * Récupères les notes de frais et les envoies à la vue principale.
	 *
	 * @param  array $status Post_status, permet d'afficher notes archivés ou publique.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function display( $status = array( 'publish', 'future' ) ) {
		$ndf_args = array(
			'post_status' => $status,
		);
		if ( ! current_user_can( 'ndf_view_all' ) ) {
			$ndf_args['author'] = get_current_user_id();
		}
		$ndfs = $this->get( $ndf_args );

		\eoxia\View_Util::exec( 'note-de-frais', 'ndf', 'main', array(
			'ndfs' => $ndfs,
			'status' => $status,
		) );
	}

	/**
	 * Récupère le nom de statut avec en fonction du code.
	 *
	 * @param  string $status Code statut @see this->status.
	 * @return string         Nom du statut @see this->status.
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function get_status( $status ) {
		return $this->status[ $status ];
	}

	/**
	 * Génères le document.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  integer $ndf_id       L'ID de la note de frais.
	 * @param  boolean $with_picture Oui pour avoir les photos. Defaut true.
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

		$ndfls = NDFL_Class::g()->get( array(
			'post_parent' => $ndf_id,
		) );

		$user = User_Class::g()->get( array(
			'include' => array( $ndf->author_id ),
		), true );

		$types_de_note = Type_Note_Class::g()->get();
		$list_type_de_note = array();
		foreach ( $types_de_note as $type_de_note ) {
			$list_type_de_note[ $type_de_note->id ] = $type_de_note->category_id . ' : ' . $type_de_note->name;
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
		$sheet_details['miseajour'] = $ndf->date_modified['date_human_readable'];

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
									'size' => 10,
								),
							);
						}

						$sheet_details['ndf_medias']['value'][] = array(
							'id_media' => $ndfl->thumbnail_id,
							'media' => $picture,
						);
					}
				}

				$sheet_details['ndf']['value'][] = array(
					'date' => $ndfl->date['date_human_readable'],
					'libelle' => $ndfl->title,
					'categorie' => ! empty( $ndfl->taxonomy[ Type_Note_Class::g()->get_taxonomy() ] ) && ! empty( $ndfl->taxonomy[ Type_Note_Class::g()->get_taxonomy() ][0] ) ? $list_type_de_note[ $ndfl->taxonomy[ Type_Note_Class::g()->get_taxonomy() ][0]->term_id ] : $ndfl->category_name,
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

NDF_Class::g();
