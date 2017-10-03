<?php
/**
 * Classe gérant les notes de frais.
 *
 * @author eoxia
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2017 Eoxia
 * @package ndf
 * @subpackage class
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
}

NDF_Class::g();
