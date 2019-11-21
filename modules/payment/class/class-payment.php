<?php
/**
 * Classe gérant les paiements.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2017-2019 Eoxia
 * @package Eoxia/NodeDeFrais
 */

namespace frais_pro;

defined( 'ABSPATH' ) || exit;

/**
 * Classe gérant les paiements.
 */
class Payment extends \eoxia\Comment_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\frais_pro\Payment_Model';

	/**
	 * Le post type
	 *
	 * @var string
	 */
	protected $type = 'fp_payment';

	/**
	 * Slug de base pour la route dans l'api rest
	 *
	 * @var string
	 */
	protected $base = 'payment';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key = 'fp_payment';

	/**
	 * Le préfixe de la note
	 *
	 * @var string
	 */
	public $element_prefix = 'ER';

	/**
	 * Le nom pour le resgister post type
	 *
	 * @var string
	 */
	protected $post_type_name = 'Payment';
}

Payment::g();
