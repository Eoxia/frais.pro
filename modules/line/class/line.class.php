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

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les lignes de notes de frais.
 */
class Line_Class extends \eoxia\Post_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\frais_pro\Line_Model';

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
	protected $before_post_function = array( '\frais_pro\before_update_ndfl' );


	/**
	 * Fonction de callback avant de mêttre à jour les données en mode PUT.
	 *
	 * @var array
	 */
	protected $before_put_function = array( '\frais_pro\before_update_ndfl' );

	/**
	 * La fonction appelée automatiquement avant la création de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $after_post_function = array( '\frais_pro\after_update_ndfl' );

	/**
	 * La fonction appelée automatiquement avant la modification de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $after_put_function = array( '\frais_pro\after_update_ndfl' );

	/**
	 * La fonction appelée automatiquement avant la modification de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $after_get_function = array( '\frais_pro\get_current_category' );

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
		$template_vars['ndf'] = Note_Class::g()->get( array(
			'id' => $ndf_id,
		), true );
		$template_vars['user'] = User_Class::g()->get( array(
			'id' => get_current_user_id(),
		), true );
		$template_vars['display_mode'] = $display_mode;
		$template_vars['ndf_is_closed'] = in_array( $template_vars['ndf']->validation_status, Note_Class::g()->closed_status, true ) ? true : false;

		\eoxia\View_Util::exec( 'frais-pro', 'line', 'main', $template_vars );
	}

	/**
	 * Vérifie si une ligne de note est valide ou non
	 *
	 * @param  array|object $line La définition de la ligne à vérifier.
	 *
	 * @return array       Le statut de la ligne avec le détail si elle n'est pas valide.
	 */
	public function check_line_status( $line ) {
		$line_state = array(
			'status' => true,
			'errors' => array(),
		);

		foreach ( \eoxia\Config_Util::$init['frais-pro']->line->line_mandatory_values as $field_key => $field_details ) {
			if ( empty( $field_details ) ) {
				if ( empty( $line->$field_key ) ) {
					$line_state['status'] = false;
					$line_state['errors'][] = $field_key;
				}
			} else {
				$has_error = true;
				foreach ( $field_details as $key ) {
					if ( ! empty( $line->$key ) ) {
						$has_error = false;
					}
				}

				if ( $has_error ) {
					$line_state['status'] = false;
					$line_state['errors'][] = $field_key;
				}
			}
		}

		return $line_state;
	}

}

Line_Class::g();
