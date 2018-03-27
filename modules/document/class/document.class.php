<?php
/**
 * Classe principales gérant les documents ODT.
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
class Document_Class extends \eoxia\Attachment_Class {

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
	protected $type = 'note-papier';

	/**
	 * Nom de la taxonomy
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = 'attachment_category';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key = 'fp_document';

	/**
	 * Le préfixe de la note
	 *
	 * @var string
	 */
	public $element_prefix = 'NP';

	/**
	 * Slug de base pour la route dans l'api rest
	 *
	 * @var string
	 */
	protected $base = 'note-papier';

	/**
	 * Le chemin vers les modèles
	 *
	 * @var string
	 */
	protected $model_path = PLUGIN_NOTE_DE_FRAIS_PATH;

	/**
	 * [generate_file description]
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param  [type] $document  [description]
	 * @param  string $extension [description]
	 *
	 * @return [type]            [description]
	 */
	public function generate_file( $document, $extension = 'odt' ) {
		$response = array(
			'status'  => false,
			'message' => '',
			'path'    => '',
			'url'     => '',
		);

		$path          = $this->get_dir_path() . '/ndf';
		$document_path = $path . '/' . $document->data['title'];
		$document_url  = $this->get_dir_path( 'baseurl' ) . '/ndf';
		$document_url .= '/' . $document->data['title'];

		// Vérification de l'existence du dossier de destination.
		if ( ! is_dir( dirname( $document_path ) ) ) {
			wp_mkdir_p( dirname( $document_path ) );
		}

		$mime_types = array();
		if ( 'odt' === $extension ) {
			$response['status'] = $this->generate_odt( $document, $document_path );
			$mime_types = array( 'odt' => 'application/vnd.oasis.opendocument.text' );
		} elseif ( 'csv' === $extension ) {
			$response['status'] = $this->generate_csv( $document, $document_path );
			$mime_types = array( 'csv' => 'text/csv' );
		}

		// Dans le cas ou le fichier a bien été généré, on met a jour les informations dans la base de données.
		if ( is_file( $document_path ) ) {
			$response['status']  = true;
			$response['path']    = $document_path;
			$response['url']     = $document_url;
			$response['endpath'] = str_replace( $this->get_dir_path(), '', $response['path'] );

			// On rajoute la métadonnée "_wp_attached_file" de WordPress.
			$document->data['_wp_attached_file'] = $response['endpath'];

			$file_mime_type              = wp_check_filetype( $document_path, $mime_types );
			$document->data['mime_type'] = $file_mime_type['type'];
			$this->update( $document->data, true );
		}

		return $response;
	}

	/**
	 * Création d'un fichier odt a partir d'un modèle de document donné et d'un modèle de donnée
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 *
	 * @param Document_Model $document Les données de l'ODT.
	 *
	 * array['status']   boolean True si tout s'est bien passé sinon false.
	 * array['message']  string  Le message informatif de la méthode.
	 * array['path']     string  Le chemin absolu vers le fichier.
	 * array['url']      string  L'url vers le fichier.
	 *
	 * @return array                   (Voir au dessus).
	 */
	public function generate_odt( $document, $document_path ) {
		@ini_set( 'memory_limit', '256M' );

		$config = array(
			'PATH_TO_TMP' => $this->get_dir_path() . '/ndf/tmp',
		);

		if ( ! is_dir( $config['PATH_TO_TMP'] ) ) {
			wp_mkdir_p( $config['PATH_TO_TMP'] );
		}

		require_once PLUGIN_NOTE_DE_FRAIS_PATH . '/core/external/odtPhpLibrary/odf.php';

		$odf_php_lib = new \NdfOdf( $document->data['model_path'], $config );

		// Vérification de l'existence d'un contenu a écrire dans le document.
		if ( ! empty( $document->data['document_meta'] ) ) {
			// Lecture du contenu à écrire dans le document.
			foreach ( $document->data['document_meta'] as $data_key => $data_value ) {
				if ( is_array( $data_value ) && ! empty( $data_value['raw'] ) ) {
					$data_value = $data_value['raw'];
				}

				$odf_php_lib = $this->set_document_meta( $data_key, $data_value, $odf_php_lib );
			}
		}


		// Enregistrement du document sur le disque.
		// @todo: Vérifier le status ici
		$odf_php_lib->saveToDisk( $document_path );

		return true;
	}

	/**
	 * Génères le fichier CSV.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param Document_Model $document Les données du CSV.
	 *
	 * array['status']   boolean True si tout s'est bien passé sinon false.
	 * array['message']  string  Le message informatif de la méthode.
	 * array['path']     string  Le chemin absolu vers le fichier.
	 * array['url']      string  L'url vers le fichier.
	 *
	 * @return array                   (Voir au dessus).
	 */
	public function generate_csv( $document, $document_path ) {
		ob_start();
		require $document->data['model_path'];
		$csv_file_content = ob_get_clean();

		foreach ( $document->data['document_meta'] as $key => $value ) {
			if ( 'ndf' !== $key && 'ndf_medias' !== $key ) {
				$csv_file_content = str_replace( '{' . $key . '}', $value, $csv_file_content );
			} elseif ( 'ndf' === $key ) {
				$file_lines = '';
				foreach ( $value['value'] as $line ) {
					unset( $line['id_media_attached'] );
					unset( $line['attached_media'] );
					$file_lines .= '"' . implode( '";"', $line ) . '"
';
				}
				$csv_file_content = str_replace( '{LignesDeFrais}', $file_lines, $csv_file_content );
			}
		}

		$csv_file_handler = fopen( $document_path, 'w' );
		fwrite( $csv_file_handler, $csv_file_content );
		fclose( $csv_file_handler );

		return true;
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
	 * Récupère et affiche la liste des documents associés à une note.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
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
	 * @since 1.0.0
	 * @version 1.0.0
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
	 * Création des types de documents (taxonomies)
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function init_document_type() {
		$document_types = array(
			'note'       => __( 'Note under odt format without picture', 'frais-pro' ),
			'note-photo' => __( 'Note under odt format with picture', 'frais-pro' ),
			'note-csv'   => __( 'Note under csv format', 'frais-pro' ),
			'printed'    => __( 'Printed/Generated document', 'frais-pro' ),
		);

		foreach ( $document_types as $type_slug => $type_name ) {
			$term_check = term_exists( $type_slug, $this->attached_taxonomy_type );
			if ( 0 === $term_check || null === $term_check ) {
				wp_insert_term( $type_name, $this->attached_taxonomy_type, array( 'slug' => $type_slug ) );
			}
		}
	}

}

Document_Class::g();
