<?php
/**
 * Classe principales gérant les documents ODT et CSV.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe principales gérant les documents ODT et CSV.
 */
class Document_Class extends \eoxia\Post_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\frais_pro\Document_Model';

	/**
	 * Le post type
	 *
	 * @var string
	 */
	protected $post_type = 'attachment';

	/**
	 * Nom de la taxonomy
	 *
	 * @var string
	 */
	public $attached_taxonomy_type = 'attachment_category';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key = 'fp_document';

	/**
	 * Le préfixe de l'objet.
	 *
	 * @var string
	 */
	public $element_prefix = 'N';

	/**
	 * Fonction de callback avant de modifier les données en mode PUT.
	 *
	 * @var array
	 */
	protected $before_put_function = array();

	/**
	 * Fonction de callback après la modification des données.
	 *
	 * @var array
	 */
	protected $after_get_function = array();

	/**
	 * Slug de base pour la route dans l'api rest
	 *
	 * @var string
	 */
	protected $base = 'note-papier';

	/**
	 * Récupères le chemin vers le dossier frais-pro dans wp-content/uploads
	 *
	 * @param string $path_type (Optional) Le type de path.
	 *
	 * @return string Le chemin vers le document
	 */
	public function get_dir_path( $path_type = 'basedir' ) {
		$upload_dir = wp_upload_dir();
		return str_replace( '\\', '/', $upload_dir[ $path_type ] ) . '/ndf';
	}

	/**
	 * Récupération de la liste des modèles de fichiers disponible pour un type d'élément
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param array $current_element_type La liste des types pour lesquels il faut récupérer les modèles de documents.
	 * @return array                      Un statut pour la réponse, un message si une erreur est survenue, le ou les identifiants des modèles si existants.
	 */
	public function get_model_for_element( $current_element_type ) {
		$response = array(
			'status'     => true,
			'model_id'   => null,
			'model_path' => str_replace( '\\', '/', PLUGIN_NOTE_DE_FRAIS_PATH . 'core/assets/document_template/' . $current_element_type[0] . '.odt' ),
			'model_url'  => str_replace( '\\', '/', PLUGIN_NOTE_DE_FRAIS_URL . 'core/assets/document_template/' . $current_element_type[0] . '.odt' ),
			// translators: Pour exemple: Le modèle utilisé est: C:\wamp\www\wordpress\wp-content\plugins\digirisk-alpha\core\assets\document_template\document_unique.odt.
			'message'    => sprintf( __( 'Le modèle utilisé est: %1$score/assets/document_template/%2$s.odt', 'digirisk' ), PLUGIN_NOTE_DE_FRAIS_PATH, $current_element_type[0] ),
		);

		$tax_query = array(
			'relation' => 'AND',
		);

		if ( ! empty( $current_element_type ) ) {
			foreach ( $current_element_type as $element ) {
				$tax_query[] = array(
					'taxonomy' => $this->attached_taxonomy_type,
					'field'    => 'slug',
					'terms'    => $element,
				);
			}
		}

		$query = new \WP_Query( array(
			'fields'         => 'ids',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'tax_query'      => $tax_query,
			'post_type'      => 'attachment',
		) );

		if ( $query->have_posts() ) {
			$upload_dir = wp_upload_dir();

			$model_id               = $query->posts[0];
			$attachment_file_path   = str_replace( '\\', '/', get_attached_file( $model_id ) );
			$response['model_id']   = $model_id;
			$response['model_path'] = str_replace( '\\', '/', $attachment_file_path );
			$response['model_url']  = str_replace( str_replace( '\\', '/', $upload_dir['basedir'] ), str_replace( '\\', '/', $upload_dir['baseurl'] ), $attachment_file_path );

			// translators: Pour exemple: Le modèle utilisé est: C:\wamp\www\wordpress\wp-content\plugins\digirisk-alpha\core\assets\document_template\document_unique.odt.
			$response['message'] = sprintf( __( 'Le modèle utilisé est: %1$s', 'frais-pro' ), $attachment_file_path );
		}

		return $response;
	}

	/**
	 * Création d'un fichier odt a partir d'un modèle de document donné et d'un modèle de donnée
	 *
	 * @since 1.0.0
	 * @version 6.4.0
	 *
	 * @param string $model_path       Le chemin vers le fichier modèle a utiliser pour la génération.
	 * @param array  $document_content Un tableau contenant le contenu du fichier à écrire selon l'élément en cours d'impression.
	 * @param string $document_name    Le nom du document.
	 *
	 * array['status']   boolean True si tout s'est bien passé sinon false.
	 * array['message']  string  Le message informatif de la méthode.
	 * array['path']     string  Le chemin absolu vers le fichier.
	 * array['url']      string  L'url vers le fichier.
	 *
	 * @return array                   (Voir au dessus).
	 */
	public function generate_document( $model_path, $document_content, $document_name ) {
		$response = array(
			'status'  => false,
			'message' => '',
			'path'    => '',
			'url'     => '',
		);

		require_once PLUGIN_NOTE_DE_FRAIS_PATH . '/core/external/odtPhpLibrary/odf.php';

		$path          = $this->get_dir_path();
		$document_path = $path . '/' . $document_name;
		$document_url  = $this->get_dir_path( 'baseurl' );
		$document_url .= '/' . $document_name;

		$config = array(
			'PATH_TO_TMP' => $path . '/tmp',
		);

		if ( ! is_dir( $config['PATH_TO_TMP'] ) ) {
			wp_mkdir_p( $config['PATH_TO_TMP'] );
		}

		// On créé l'instance pour la génération du document odt.
		@ini_set( 'memory_limit', '256M' );
		$odf_php_lib = new \NdfOdf( $model_path, $config );

		// Vérification de l'existence d'un contenu a écrire dans le document.
		if ( ! empty( $document_content ) ) {
			// Lecture du contenu à écrire dans le document.
			foreach ( $document_content as $data_key => $data_value ) {
				if ( is_array( $data_value ) && ! empty( $data_value['raw'] ) ) {
					$data_value = $data_value['raw'];
				}

				$odf_php_lib = $this->set_document_meta( $data_key, $data_value, $odf_php_lib );
			}
		}

		// Vérification de l'existence du dossier de destination.
		if ( ! is_dir( dirname( $document_path ) ) ) {
			wp_mkdir_p( dirname( $document_path ) );
		}

		// Enregistrement du document sur le disque.
		$odf_php_lib->saveToDisk( $document_path );

		// Dans le cas ou le fichier a bien été généré, on met a jour les informations dans la base de données.
		if ( is_file( $document_path ) ) {
			$response['status'] = true;
			$response['path']   = $document_path;
			$response['url']    = $document_url;
		}

		return $response;
	}

	/**
	 * Ecris dans le document ODT
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 *
	 * @param string $data_key    La clé dans le ODT.
	 * @param string $data_value  La valeur de la clé.
	 * @param object $current_odf Le document courant.
	 *
	 * @return object             Le document courant
	 */
	public function set_document_meta( $data_key, $data_value, $current_odf ) {
		// Dans le cas où la donnée a écrire est une valeur "simple" (texte).
		if ( ! is_array( $data_value ) ) {
			$current_odf->setVars( $data_key, stripslashes( $data_value ), true, 'UTF-8' );
		} else if ( is_array( $data_value ) && isset( $data_value[ 'type' ] ) && !empty( $data_value[ 'type' ] ) ) {
			switch ( $data_value[ 'type' ] ) {

				case 'picture':
					$current_odf->setImage( $data_key, $data_value[ 'value' ], ( !empty( $data_value[ 'option' ] ) && !empty( $data_value[ 'option' ][ 'size' ] ) ? $data_value[ 'option' ][ 'size' ] : 0 ) );
					break;

				case 'segment':
					$segment = $current_odf->setndfSegment( $data_key );

					if ( $segment && is_array( $data_value[ 'value' ] ) ) {
						foreach ( $data_value[ 'value' ] as $segment_detail ) {
							foreach ( $segment_detail as $segment_detail_key => $segment_detail_value ) {
								if ( is_array( $segment_detail_value ) && array_key_exists( 'type', $segment_detail_value ) && ( 'sub_segment' == $segment_detail_value[ 'type' ] ) ) {
									foreach ( $segment_detail_value[ 'value' ] as $sub_segment_data ) {
										foreach ( $sub_segment_data as $sub_segment_data_key => $sub_segment_data_value ) {
											$segment->$segment_detail_key = $this->set_document_meta( $sub_segment_data_key, $sub_segment_data_value, $segment );
										}
									}
								}
								else {
									$segment = $this->set_document_meta( $segment_detail_key, $segment_detail_value, $segment );
								}
							}

							$segment->merge();
						}

						$current_odf->mergendfSegment( $segment );
					}
					unset( $segment );
					break;
			}
		}

		return $current_odf;
	}


	/**
	 * Création du document dans la base de données puis appel de la fonction de génération du fichier
	 *
	 * @since 1.0.0
	 * @version 6.4.0
	 *
	 * @param object $element      L'élément pour lequel il faut créer le document
	 * @param array $document_type Les catégories auxquelles associer le document généré
	 * @param array $document_meta Les données a écrire dans le modèle de document
	 *
	 * @return object              Le résultat de la création du document
	 */
	public function create_document( $element, $document_type, $document_meta ) {
		$types = $document_type;

		$response = array(
			'status'   => true,
			'message'  => '',
			'filename' => '',
			'path'     => '',
		);

		// Définition du modèle de document a utiliser pour l'impression.
		$model_to_use   = null;
		$model_response = $this->get_model_for_element( wp_parse_args( array( 'model', 'default_model' ), $document_type ) );
		$model_to_use   = $model_response['model_path'];

		// Définition de la révision du document.
		// $document_revision = $this->get_document_type_next_revision( $this->post_type, $element->id );

		// Définition de la partie principale du nom de fichier.
		$main_title_part = $types[0] . '_' . $element->title;
		$response['filename'] = mysql2date( 'Ymd', current_time( 'mysql', 0 ) ) . '_';
		$response['filename'] .= sanitize_title( str_replace( ' ', '_', $main_title_part ) ) . '.odt';

		if ( null === $model_to_use ) {
			$response['status'] = false;
			$response['message'] = __( 'No model to use for generate odt file', 'frais-pro' );
			return $response;
		}

		$response['path'] = $response['filename'];

		if ( ! empty( $element ) ) {
			$response['path'] = $element->type . '/' . $element->id . '/' . $response['filename'];
		}

		// Génères le fichier ODT.
		$document_creation = $this->generate_document( $model_to_use, $document_meta, $response['path'] );

		if ( ! $document_creation['status'] ) {
			$response['status'] = false;
			$response['message'] = __( 'Error when generated odt file', 'frais-pro' );
			return $response;
		}

		$filetype = wp_check_filetype( $document_creation['path'], null );
		$response['path'] = $document_creation['path'];

		// Enregistre le fichier et ses métadonnées dans la base de donnée.
		$document_args = array(
			'post_status'    => 'inherit',
			'post_title'     => basename( $response['filename'] ),
			'post_parent'    => $element->id,
			'post_type'      => $this->post_type,
			'guid'           => $document_creation['url'],
			'post_mime_type' => $filetype['type'],
		);

		$response['id'] = wp_insert_attachment( $document_args, $this->get_dir_path() . '/' . $response['path'], $element->id );

		$attach_data = wp_generate_attachment_metadata( $response['id'], $this->get_dir_path() . '/' . $response['path'] );
		wp_update_attachment_metadata( $response['id'], $attach_data );

		wp_set_object_terms( $response['id'], wp_parse_args( $types, array( 'printed', ) ), $this->attached_taxonomy_type );

		//	On met à jour les informations concernant le document dans la base de données.
		$document_args = array(
			'id'            => $response['id'],
			'model_id'      => $model_to_use,
			// 'document_meta' => $document_meta,
		);

		$this->update( $document_args, false );

		return $response;
	}

	/**
	 * Récupère et affiche la liste des documents associés à une note.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param array $args Arguments pour récupérer les documents.
	 *
	 * @return void
	 */
	public function display_list( $args = array() ) {
		$document_list = $this->get( array(
			'post_parent' => $args['id'],
		) );


		\eoxia\View_Util::exec( 'frais-pro', 'document', 'list', array(
			'documents' => $document_list,
		) );
	}

	/**
	 * Récupère et affiche la liste des documents associés à une note.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param array $document Arguments pour récupérer les documents.
	 *
	 * @return void
	 */
	public function display_item( $document ) {
		$document_checked = $this->check_file( $document );

		\eoxia\View_Util::exec( 'frais-pro', 'document', 'item', array(
			'document'         => $document,
			'document_checked' => $document_checked,
		) );
	}

	/**
	 * Vérification de l'existence d'un fichier à partir de la définition d'un document.
	 *
	 * 1- On remplace l'url du site "site_url( '/' )" par le chemin "ABSPATH" contenant les fichiers du site: on vérifie si le fichier existe.
	 * 2- Si le fichier n'existe pas:
	 *  2.a- On récupère la meta associée automatiqumeent par WordPress.
	 *  2.b- Si la méta n'est pas vide, on vérifie que sa valeur concaténée au chemin absolu des uploads "wp_upload_dir()" de WordPress soit bien un fichier
	 *
	 * @param Document_Model $document La définition du document à vérifier.
	 *
	 * @return array                   Tableau avec le statuts d'existence du fichier (True/False) et le lien de téléchargement du fichier.
	 */
	public function check_file( $document ) {
		// Définition des valeurs par défaut.
		$file_check = array(
			'exists'    => false,
			'path'      => str_replace( site_url( '/' ), ABSPATH, $document['link'] ),
			'mime_type' => '',
			'link'      => $document['link'],
		);
		$upload_dir = wp_upload_dir();

		// Vérification principale. cf 1 ci-dessus.
		if ( is_file( $file_check['path'] ) ) {
			$file_check['exists'] = true;
		}

		// La vérification principale n'a pas fonctionnée. cf 2 ci-dessus.
		if ( ! $file_check['exists'] && ! empty( $document['_wp_attached_file'] ) ) {
			$file_check['path'] = $upload_dir['basedir'] . '/' . $document['_wp_attached_file'];
			$file_check['link'] = $upload_dir['baseurl'] . '/' . $document['_wp_attached_file'];
			if ( is_file( $file_check['path'] ) ) {
				$file_check['exists'] = true;
			}
		}

		// Si le fichier existe on récupère le type mime.
		if ( $file_check['exists'] ) {
			$file_check['mime_type'] = wp_check_filetype( $file_check['path'] );
		}

		return $file_check;
	}

}

document_class::g();
