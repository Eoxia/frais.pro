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
	protected $type = 'fp_note';

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
	 * Le préfixe de la note
	 *
	 * @var string
	 */
	public $element_prefix = 'N';

	/**
	 * Définition des fonctions de callback pour l'élément.
	 *
	 * @var  array
	 */
	protected $callback_func = array(
		'before_post' => array( '\frais_pro\before_get_identifier', '\frais_pro\set_note_name' ),
		'after_get'   => array( '\frais_pro\get_full_note' ),
		'after_put'   => array( '\frais_pro\get_full_note' ),
	);

	/**
	 * Le nom pour le resgister post type
	 *
	 * @var string
	 */
	protected $post_type_name = 'Note';

	/**
	 * Fait l'affichage principale du module "note".
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 */
	public function display() {
		$user = User_Class::g()->get( array( 'id' => get_current_user_id() ), true );

		\eoxia\View_Util::exec( 'frais-pro', 'note', 'main', array(
			'user' => $user->data,
		) );
	}

	/**
	 * Récupères les notes de frais et les envoies à la vue "list".
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 *
	 * @param  array $args         Arguments pour le get.
	 *
	 * @return void
	 */
	public function display_list( $args = array() ) {
		$default_status = array( 'publish', 'future' );

		$args_note_list = array(
			'post_status'           => $default_status,
			'display_only_has_note' => false,
		);

		$args_note_list = wp_parse_args( $args, $args_note_list );

		if ( ! current_user_can( 'frais_pro_view_all_user_sheets' ) ) {
			$args_note_list['author'] = get_current_user_id();
		}

		$note_list = $this->get( $args_note_list );

		if ( ! isset( $args['display_only_has_note'] ) || ! $args['display_only_has_note'] || ( $args['display_only_has_note'] && ! empty( $note_list ) ) ) {
			\eoxia\View_Util::g()->exec( 'frais-pro', 'note', 'list', array(
				'note_list'    => $note_list,
				'custom_class' => ( ! empty( $args ) && ! empty( $args['custom_css'] ) && is_array( $args['custom_css'] ) ? implode( ' ', $args['custom_css'] ) : '' ),
			) );
		}
	}

	/**
	 * Récupères les notes et les lignes puis appel la vue "single".
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param  integer $note_id ID de la note.
	 *
	 * @return void
	 */
	public function display_single( $note_id = 0 ) {
		if ( empty( $note_id ) ) {
			$note_id = ! empty( $_GET['note'] ) ? (int) $_GET['note'] : 0; // WPCS: CSRF is ok.
		}

		$current_note = $this->get( array( 'id' => $note_id ), true );
		$status_list  = Note_Status_Class::g()->get();

		$note_is_closed = ! empty( $current_note->data['current_status']->data['special_treatment'] ) && ( 'closed' === $current_note->data['current_status']->data['special_treatment'] ) ? true : false;

		$view = 'single';
		if ( $current_note->data['contains_unaffected'] ) {
			$view = 'single-unaffected';
		}

		\eoxia\View_Util::exec( 'frais-pro', 'note', $view, array(
			'note_is_closed' => $note_is_closed,
			'display_mode'   => ! $note_is_closed ? 'grid' : 'list',
			'note'           => $current_note,
			'lines'          => Line_Class::g()->get( array( 'post_parent' => $note_id ) ),
			'status_list'    => $status_list,
		) );
	}

	/**
	 * Créer la note pour les lignes désaffectées.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param integer $author_id L'ID du créateur de la note ou la ligne a été dissocié.
	 *
	 * @return Note_Model Les données de la note.
	 */
	public function create_unaffected_note( $author_id ) {
		$title = __( 'Unaffected lines', 'frais-pro' );
		$name  = sanitize_title( $title );

		$user = User_Class::g()->get( array(
			'include' => $author_id,
		), true );

		$note = $this->get( array(
			'author' => $author_id,
			'name'   => $name . '-' . $user->data['displayname'],
		), true );

		if ( empty( $note ) ) {
			$note = $this->create( array(
				'title'               => $title . '-' . $user->data['displayname'],
				'author_id'           => $author_id,
				'slug'                => $name . '-' . $user->data['displayname'],
				'status'              => 'publish',
				'contains_unaffected' => true,
			) );
		}

		return $note;
	}

	/**
	 * Génères le document.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  integer $note_id   L'ID de la note de frais.
	 * @param  string  $type      Type de fichier a exporter.
	 * @param  string  $extension L'extension du fichier.
	 *
	 * @return array {
	 *         Les propriétés du tableau.
	 *
	 *         @type string ....
	 * }.
	 */
	public function generate_document( $note_id, $type = '', $extension = '' ) {
		$total_tax_inclusive_amount = 0;
		$total_tax_amount           = 0;


		$note = $this->get( array(
			'id' => $note_id,
		), true );

		$lines = Line_Class::g()->get( array(
			'post_parent' => $note->data['id'],
		) );

		$user = User_Class::g()->get( array(
			'include' => array( $note->data['author_id'] ),
		), true );

		$types_de_note     = Line_Type_Class::g()->get();
		$list_type_de_note = array();
		foreach ( $types_de_note as $type_de_note ) {
			$list_type_de_note[ $type_de_note->data['id'] ]['id']   = $type_de_note->data['category_id'];
			$list_type_de_note[ $type_de_note->data['id'] ]['name'] = $type_de_note->data['name'];
		}

		$sheet_details = array(
			'ndf'        => array(
				'type'  => 'segment',
				'value' => array(),
			),
			'ndf_medias' => array(
				'type'  => 'segment',
				'value' => array(),
			),
		);

		$periode = substr( $note->data['title'], 0, 4 ) . '/' . substr( $note->data['title'], 4, 2 );

		if ( ! empty( $user->firstname ) && ! empty( $user->data['lastname'] ) ) {
			$sheet_details['utilisateur_prenom_nom'] = $user->data['firstname'] . ' ' . $user->data['lastname'];
		}
		$sheet_details['utilisateur_email'] = $user->data['email'];
		$sheet_details['periode']           = $periode;

		if ( empty( $sheet_details['utilisateur_prenom_nom'] ) ) {
			$sheet_details['utilisateur_prenom_nom'] = $user->data['login'];
		}

		$sheet_details['statut']    = $note->data['current_status']->data['name'];
		$sheet_details['miseajour'] = $note->data['date_modified']['rendered']['date_human_readable'];

		if ( ! empty( $lines ) ) {
			foreach ( $lines as $line ) {
				if ( 'note-photo' === $type ) {
					$picture = '-';
					if ( ! empty( $line->data['thumbnail_id'] ) ) {
						$picture_definition = wp_get_attachment_image_src( $line->data['thumbnail_id'], 'large' );
						$picture_final_path = str_replace( '\\', '/', str_replace( site_url( '/' ), ABSPATH, $picture_definition[0] ) );
						if ( is_file( $picture_final_path ) ) {
							$picture = array(
								'type'   => 'picture',
								'value'  => $picture_final_path,
								'option' => array(
									'size' => 8,
								),
							);
						}

						$sheet_details['ndf_medias']['value'][] = array(
							'id_media' => $line->data['thumbnail_id'],
							'media'    => $picture,
						);
					}
				}

				$categorie_id    = '-';
				$categorie_label = $line->data['category_name'];

				if ( ! empty( $line->data['current_category'] ) ) {
					$categorie_id    = $line->data['current_category']->data['category_id'];
					$categorie_label = $line->data['current_category']->data['name'];
				}

				$sheet_details['ndf']['value'][] = array(
					'id_ligne'          => $line->data['id'],
					'date'              => $line->data['date_modified']['rendered']['date_time'],
					'libelle'           => ! empty( $line->data['title'] ) ? $line->data['title'] : '-',
					'num_categorie'     => $categorie_id,
					'nom_categorie'     => $categorie_label,
					'km'                => $line->data['distance'],
					'ttc'               => $line->data['tax_inclusive_amount'] . '€',
					'tva'               => $line->data['tax_amount'] . '€',
					'id_media_attached' => ! empty( $line->data['thumbnail_id'] ) ? $line->data['thumbnail_id'] : '',
					'attached_media'    => ( 'note-photo' === $type ) ? $picture : '',
				);

				$total_tax_inclusive_amount += $line->data['tax_inclusive_amount'];
				$total_tax_amount           += $line->data['tax_amount'];
			}
		}

		$sheet_details['totaltva'] = $total_tax_amount . '€';
		$sheet_details['totalttc'] = $total_tax_inclusive_amount . '€';
		$sheet_details['marque']   = $user->data['marque'];
		$sheet_details['chevaux']  = $user->data['chevaux'];
		$sheet_details['prixkm']   = $user->data['prixkm'];

		$document                    = Document_Class::g()->get( array( 'schema' => true ), true );
		$document->data['parent_id'] = $note->data['id'];

		$args_title = array(
			current_time( 'Ymd' ),
			strtolower( str_replace( '-', '_', sanitize_title( $note->data['title'] ) ) ),
			$note->data['unique_identifier'],
			$document->data['unique_identifier'],
			strtolower( str_replace( '-', '_', sanitize_title( $type ) ) ),
		);

		$document->data['title']  = implode( '_', $args_title );
		$document->data['title'] .= '.' . $extension;

		$response = Document_Class::g()->create_document( $document, array( $type ), $sheet_details, $extension );

		return $response;
	}

}

Note_Class::g();
