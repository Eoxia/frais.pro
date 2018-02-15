<?php namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Document_Class extends \eoxia\Post_Class {
	protected $model_name   				= '\frais_pro\document_model';
	protected $post_type    				= 'attachment';
	public $attached_taxonomy_type  = 'attachment_category';
	protected $meta_key    					= '_wpdigi_document';
	protected $before_put_function = array();
	protected $after_get_function = array();

	/**
	 * Slug de base pour la route dans l'api rest
	 *
	 * @var string
	 */
	protected $base  = 'note-papier';


	public $mime_type_link = array(
		'application/vnd.oasis.opendocument.text' => '.odt',
		'application/zip' => '.zip',
	);

	/**
	 * Instanciation de la gestion des document imprimés / Instanciate printes document
	 */
 	public function construct() {
 		parent::construct();
 	}

	/**
	* Récupères le chemin vers le dossier digirisk dans wp-content/uploads
	*
	* @param string $path_type (Optional) Le type de path
	*
	* @return string Le chemin vers le document
	*/
	public function get_digirisk_dir_path( $path_type = 'basedir' ) {
		$upload_dir = wp_upload_dir();
		return str_replace( '\\', '/', $upload_dir[ $path_type ] ) . '/ndf';
	}

	/**
	 * Création d'un fichier odt a partir d'un modèle de document donné et d'un modèle de donnée / Create an "odt" file from a given document model and a data model
	 *
	 * @param string $model_path Le chemin vers le fichier modèle a utiliser pour la génération / The path to model file to use for generate the final document
	 * @param array $document_content Un tableau contenant le contenu du fichier a écrire selon l'élément en cours d'impression / An array with the content for building file to print
	 * @param object $element L'élément courant sur lequel on souhaite générer un document / Current element where the user want to generate a file for
	 *
	 */
	public function generate_document( $model_path, $document_content, $document_name ) {
		// if ( !is_string( $model_path ) || !is_array( $document_content ) || !is_string( $document_name ) ) {
		// 	return false;
		// }

		$response = array(
			'status'	=> false,
			'message'	=> '',
			'link'		=> '',
		);

		require_once( PLUGIN_NOTE_DE_FRAIS_PATH . '/core/external/odtPhpLibrary/odf.php');

		$digirisk_directory = $this->get_digirisk_dir_path();
		$document_path = $digirisk_directory . '/' . $document_name;

		$config = array(
			'PATH_TO_TMP' => $digirisk_directory . '/tmp',
		);
		if( !is_dir( $config[ 'PATH_TO_TMP' ] ) ) {
			wp_mkdir_p( $config[ 'PATH_TO_TMP' ] );
		}

		/**	On créé l'instance pour la génération du document odt / Create instance for document generation */
		@ini_set( 'memory_limit', '256M' );
		$NdfOdf = new \NdfOdf( $model_path, $config );

		/**	Vérification de l'existence d'un contenu a écrire dans le document / Check if there is content to put into file	*/
		if ( !empty( $document_content ) ) {
			/**	Lecture du contenu à écrire dans le document / Read the content to write into document	*/
			foreach ( $document_content as $data_key => $data_value ) {
				$NdfOdf = $this->set_document_meta( $data_key, $data_value, $NdfOdf );
			}
		}

		/**	Vérification de l'existence du dossier de destination / Check if final directory exists	*/
		if( !is_dir( dirname( $document_path ) ) ) {
			wp_mkdir_p( dirname( $document_path ) );
		}

		/**	Enregistrement du document sur le disque / Save the file on disk	*/
		$NdfOdf->saveToDisk( $document_path );

		/**	Dans le cas ou le fichier a bien été généré, on met a jour les informations dans la base de données / In case the file have been saved successfully, save information into database	*/
		if ( is_file( $document_path ) ) {
			$response[ 'status' ] = true;
			$response[ 'success' ] = true;
			$response[ 'link' ] = $document_path;
		}

		return $response;
	}

	/**
	* Ecris dans le document ODT
	*
	* @param string $data_key La clé dans le ODT.
	* @param string $data_value La valeur de la clé.
	* @param object $current_odf Le document courant
	*
	* @return object Le document courant
	*/
	public function set_document_meta( $data_key, $data_value, $current_odf ) {
		// if ( !is_string( $data_key ) || !is_string( $data_value ) || !is_object( $current_odf ) ) {
		// 	return false;
		// }
		/**	Dans le cas où la donnée a écrire est une valeur "simple" (texte) / In case the data to write is a "simple" (text) data	*/
		if ( !is_array( $data_value ) ) {
			$current_odf->setVars( $data_key, stripslashes( $data_value ), true, 'UTF-8' );
		} elseif ( is_array( $data_value ) && isset( $data_value[ 'type' ] ) && !empty( $data_value[ 'type' ] ) ) {
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
	 * Create the document into database and call the generation function / Création du document dans la base de données puis appel de la fonction de génération du fichier
	 *
	 * @param object $element The element to create the document for / L'élément pour lequel il faut créer le document.
	 * @param array  $document_type The document's categories / Les catégories auxquelles associer le document généré.
	 * @param array  $document_meta Datas to write into the document template / Les données a écrire dans le modèle de document.
	 * @param string $document_type The document type to output.
	 *
	 * @return object The result of document creation / le résultat de la création du document
	 */
	public function create_document( $element, $document_meta, $with_picture, $document_type ) {
		/**	Définition de la partie principale du nom de fichier / Define the main part of file name	*/
		$main_title_part = $element->title;

		/**	Enregistrement de la fiche dans la base de donnée. */
		$document_args = array(
			'post_content' => '',
			'post_status' => 'inherit',
			'post_author' => get_current_user_id(),
			'post_date' => current_time( 'mysql' ),
			'post_title' => basename( 'test', '.odt' ),
		);

		/**	On créé le document / Create the document	*/
		$filetype = 'unknown';

		switch ( $document_type ) {
			case 'odt':
				$response['filename'] = sanitize_title( str_replace( ' ', '_', $main_title_part ) ) . '.odt';
				$path = 'document/' . $element->id . '/' . $response['filename'];
				$template_path = str_replace( '\\', '/', PLUGIN_NOTE_DE_FRAIS_PATH . 'core/assets/document_template/ndf-photo.odt' );
				if ( ! $with_picture ) {
					$template_path = str_replace( '\\', '/', PLUGIN_NOTE_DE_FRAIS_PATH . 'core/assets/document_template/ndf.odt' );
				}
				$document_creation = $this->generate_document( $template_path, $document_meta, $path );
			break;

			case 'csv':
				$response['filename'] = sanitize_title( str_replace( ' ', '_', $main_title_part ) ) . '.csv';
				$path = 'document/' . $element->id . '/' . $response['filename'];
				ob_start();
				require( PLUGIN_NOTE_DE_FRAIS_PATH . 'core/assets/document_template/ndf.csv' );
				$csv_file_content = ob_get_clean();
				foreach ( $document_meta as $key => $value ) {
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
				/**	Vérification de l'existence du dossier de destination / Check if final directory exists	*/
				if( !is_dir( dirname( $this->get_digirisk_dir_path() . '/' . $path ) ) ) {
					wp_mkdir_p( dirname( $this->get_digirisk_dir_path() . '/' . $path ) );
				}

				$csv_file_handler = fopen( $this->get_digirisk_dir_path() . '/' . $path, 'w' );
				fwrite( $csv_file_handler, $csv_file_content );
				fclose( $csv_file_handler );
			break;
		}

		$response['id'] = wp_insert_attachment( $document_args, $this->get_digirisk_dir_path() . '/' . $path, $element->id );

		$attach_data = wp_generate_attachment_metadata( $response['id'], $this->get_digirisk_dir_path() . '/' . $path );
		wp_update_attachment_metadata( $response['id'], $attach_data );

		/**	On met à jour les informations concernant le document dans la base de données / Update data for document into database	*/
		$document_args = array(
			'id' => $response['id'],
			'title' => basename( $response['filename'], '.odt' ),
			'parent_id' => $element->id,
			'author_id' => get_current_user_id(),
			'date' => current_time( 'mysql' ),
			'mime_type' => ! empty( $filetype['type'] ) ? $filetype['type'] : $filetype,
			'document_meta' => $document_meta,
			'status' => 'inherit',
		);

		self::g()->update( $document_args );

		$response['link'] = $this->get_digirisk_dir_path( 'baseurl' ) . '/' . $path;

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
	 * Vérification de l'existence d'un fichier à partir de la définition d'un document.
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
		$file_check = array(
			'exists' => false,
			'link'   => '',
		);
		$upload_dir = wp_upload_dir();

		// Vérification principale. cf 1 ci-dessus.
		$file_path = str_replace( site_url( '/' ), ABSPATH, $document->link );
		if ( is_file( $file_path ) ) {
			$file_check = array(
				'exists' => true,
				'link'   => $document->link,
			);
		}

		// La vérification principale n'a pas fonctionnée. cf 2 ci-dessus.
		$wp_attached_file = get_post_meta( $document->id, '_wp_attached_file', true );
		if ( ! empty( $wp_attached_file ) ) {
			$file_to_check = $upload_dir['basedir'] . '/' . $wp_attached_file;
			if ( is_file( $file_to_check ) ) {
				$file_check = array(
					'exists' => true,
					'link'   => $upload_dir['baseurl'] . '/' . $wp_attached_file,
				);
			}
		}

		return $file_check;
	}

}

document_class::g();
