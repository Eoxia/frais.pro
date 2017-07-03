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

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Classe gérant les groupe NDF
 */
class NDF_Class extends \eoxia\Post_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name                   = '\note_de_frais\NDF_Model';

	/**
	 * Le post type
	 *
	 * @var string
	 */
	protected $post_type                    = 'ndf';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key                     = '_ndf';

	/**
	 * La fonction appelée automatiquement avant la création de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $before_post_function = array( '\note_de_frais\sanitize_ndf_class' );

	/**
	 * La fonction appelée automatiquement avant la modification de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $before_put_function = array( '\note_de_frais\sanitize_ndf_class' );

	/**
	 * La fonction appelée automatiquement après la récupération de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $after_get_function = array( '\note_de_frais\sanitize_ndf_class' );

	/**
	 * Le nom pour le resgister post type
	 *
	 * @var string
	 */
	protected $post_type_name = 'NDF';

	public function display( $group_id ) {
		\eoxia\View_Util::exec( 'note-de-frais', 'ndf', 'main', array(
			'group' => Group_NDF_Class::g()->get( array(
				'id' => $group_id,
			), true ),
			'notes' => $this->get( array(
				'post_parent' => $group_id,
			) ),
		) );
	}
}

NDF_Class::g();
