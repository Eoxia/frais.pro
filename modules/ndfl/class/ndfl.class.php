<?php
/**
 * Classe gérant les NDF
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
 * Classe gérant les lignes de notes de frais.
 */
class NDFL_Class extends \eoxia\Post_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\note_de_frais\NDFL_Model';

	/**
	 * Le post type
	 *
	 * @var string
	 */
	protected $post_type  = 'ndfl';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key   = '_ndfl';

	/**
	 * Slug de base pour la route dans l'api rest
	 *
	 * @var string
	 */
	protected $base  = 'ligne-de-frais';

	/**
	 * Fonction de callback avant d'insérer les données en mode POST.
	 *
	 * @var array
	 */
	protected $before_post_function = array( '\note_de_frais\before_update_ndfl' );


	/**
	 * Fonction de callback avant de mêttre à jour les données en mode PUT.
	 *
	 * @var array
	 */
	protected $before_put_function = array( '\note_de_frais\before_update_ndfl' );

	/**
	 * La fonction appelée automatiquement avant la création de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $after_post_function = array( '\note_de_frais\after_update_ndfl' );

	/**
	 * La fonction appelée automatiquement avant la modification de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $after_put_function = array( '\note_de_frais\after_update_ndfl' );

	/**
	 * La fonction appelée automatiquement avant la modification de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $after_get_function = array();

	/**
	 * Le nom pour le resgister post type
	 *
	 * @var string
	 */
	protected $post_type_name = 'NDFL';

	public function construct() {
		parent::construct();
	}

	public function display( $ndf_id = -1, $display_mode = 'list' ) {
		$ndfl = $this->get( array(
			'post_parent' => $ndf_id,
		) );
		$template_vars = array(
			'ndfl' => $ndfl,
		);
		$template_vars['ndf'] = NDF_Class::g()->get( array(
			'id' => $ndf_id,
		), true );
		$template_vars['user'] = User_Class::g()->get( array(
			'id' => get_current_user_id(),
		), true );
		$template_vars['display_mode'] = $display_mode;
		\eoxia\View_Util::exec( 'note-de-frais', 'ndfl', 'main', $template_vars );
	}

}

NDFL_Class::g();
