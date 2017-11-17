<?php
/**
 * Classe gérant les NDF
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 * @subpackage LigneDeFrais
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
	protected $base  = 'ligne';

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
	protected $after_get_function = array( '\note_de_frais\get_current_category' );

	/**
	 * Le nom pour le resgister post type
	 *
	 * @var string
	 */
	protected $post_type_name = 'NDFL';

	/**
	 * La taxonomy lié à ce post type.
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = '_type_note';

	/**
	 * Affichage d'une note de frais avec ses lignes
	 *
	 * @param  integer $ndf_id       Identifiant de la note de frais à récupérer.
	 * @param  string  $display_mode Quel est le mode d'affichage a utiliser.
	 */
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
		$template_vars['ndf_is_closed'] = in_array( $template_vars['ndf']->validation_status, NDF_Class::g()->closed_status, true ) ? true : false;

		\eoxia\View_Util::exec( 'note-de-frais', 'ndfl', 'main', $template_vars );
	}

}

NDFL_Class::g();
