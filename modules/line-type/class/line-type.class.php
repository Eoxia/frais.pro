<?php
/**
 * Classe gérant les types de note des notes de frais.
 *
 * @author eoxia
 * @since 1.2.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package NDF
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les actions des types de note des notes de frais.
 */
class Line_Type_Class extends \eoxia\Term_Class {

	/**
	 * Nom du modèle à utiliser
	 *
	 * @var string
	 */
	protected $model_name = '\frais_pro\Line_Type_Model';

	/**
	 * Nom de la meta stockant les donnée
	 *
	 * @var string
	 */
	protected $meta_key = 'fp_line_type';

	/**
	 * Nom de la taxonomie par défaut
	 *
	 * @var string
	 */
	protected $taxonomy = 'fp_line_type';

	/**
	 * Base de l'url pour la REST API.
	 *
	 * @var string
	 */
	protected $base = 'line_type';

	/**
	 * Instanciate
	 */
	protected function construct() {
		$this->associate_post_types = Line_Class::g()->get_type();
		parent::construct();
	}

	/**
	 * Appelle la vue pour afficher le toggle.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param integer $line (optional) La définition complète de la ligne de frais . Null par défaut.
	 * @return void
	 */
	public function display( $line = null ) {
		$line_types = self::g()->get( array(
			'taxonomy' => $this->taxonomy,
		) );

		$line_type_note = null !== $line && ! empty( $line->taxonomy[ $this->get_type() ][0] ) && ! empty( $line->taxonomy[ $this->get_type() ][0]->term_id ) ? $line->taxonomy[ $this->get_type() ][0]->term_id : 0;

		$selected_type_note_name = __( 'Line type', 'frais-pro' );
		if ( ! empty( $line_types ) && ! empty( $line_type_note ) ) {
			foreach ( $line_types as $element ) {
				if ( $element->id === (int) $line_type_note ) {
					$selected_type_note_name = $element->category_id . ' : ' . $element->name;
					break;
				}
			}
		}

		\eoxia\View_Util::exec( 'frais-pro', 'line-type', 'main', array(
			'line_types' => $line_types,
			'line' => $line,
			'line_type_note_id' => $line_type_note,
			'selected_type_note_name' => $selected_type_note_name,
		) );
	}

	/**
	 * Create default note types.
	 *
	 * @return void
	 */
	public function create_default_types() {
		$type_note = 'line-type';
		$file_content = file_get_contents( \eoxia\Config_Util::$init['frais-pro']->$type_note->path . 'assets/json/default.json' );
		$data = json_decode( $file_content, true );

		if ( ! empty( $data ) ) {
			// Utilisé pour déclarer la taxonomie à l'activation du plugin. L'action "init" n'est pas lancée à ce moment là.
			$this->callback_init();

			foreach ( $data as $category ) {
				$category['name'] = __( $category['name'], 'frais-pro' );
				$category_slug = sanitize_title( $category['category_id'] . ' : ' . $category['name'] );
				$tax = get_term_by( 'slug', $category_slug, $this->get_type(), ARRAY_A );

				if ( ! empty( $tax['term_id'] ) && is_int( $tax['term_id'] ) ) {
					$category['id'] = $tax['term_id'];
				}
				$category['slug'] = $category_slug;

				$t = $this->update( $category );
			}
		}
	}

}

Line_Type_Class::g();
