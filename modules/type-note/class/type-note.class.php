<?php
/**
 * Classe gérant les types de note des notes de frais.
 *
 * @author eoxia
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2017 Eoxia
 * @package NDF
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les actions des types de note des notes de frais.
 */
class Type_Note_Class extends \eoxia\Term_Class {

	/**
	 * Nom du modèle à utiliser
	 *
	 * @var string
	 */
	protected $model_name = '\note_de_frais\Type_Note_Model';

	/**
	 * Nom de la meta stockant les donnée
	 *
	 * @var string
	 */
	protected $meta_key = '_type_note';

	/**
	 * Nom de la taxonomie par défaut
	 *
	 * @var string
	 */
	protected $taxonomy = '_type_note';

	/**
	 * Base de l'url pour la REST API.
	 *
	 * @var string
	 */
	protected $base = 'type_note';

	/**
	 * Appelle la vue pour afficher le toggle.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @return void
	 */
	public function display() {
		$types_note = self::g()->get( array(
			'taxonomy' => $this->taxonomy,
		) );

		\eoxia\View_Util::exec( 'note-de-frais', 'type-note', 'main', array(
			'types_note' => $types_note,
		) );
	}
}

Type_Note_Class::g();
