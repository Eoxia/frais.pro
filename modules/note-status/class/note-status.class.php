<?php
/**
 * Classe gérant les statuts des notes.
 *
 * @author eoxia
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les actions des types de note des notes de frais.
 */
class Note_Status_Class extends \eoxia\Term_Class {

	/**
	 * Nom du modèle à utiliser
	 *
	 * @var string
	 */
	protected $model_name = '\frais_pro\Note_Status_Model';

	/**
	 * Nom de la meta stockant les donnée
	 *
	 * @var string
	 */
	protected $meta_key = 'fp_note_status';

	/**
	 * Nom de la taxonomie par défaut
	 *
	 * @var string
	 */
	protected $taxonomy = 'fp_note_status';

	/**
	 * Base de l'url pour la REST API.
	 *
	 * @var string
	 */
	protected $base = 'note_status';

	/**
	 * Nom du statut à afficher.
	 *
	 * @var array
	 *
	 * @todo nécessite un transfert
	 */
	public $status = array();

	/**
	 * Le ou les statuts pour lesquels on ne peut plus modifier les notes
	 *
	 * @var array
	 *
	 * @todo nécessite un transfert
	 */
	public $closed_status = array();

	/**
	 * Définition des statuts
	 */
	public function construct() {
		$this->associate_post_types = Note_Class::g()->get_type();
		parent::construct();
	}

	/**
	 * Initialise le tableau des status de note.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function init_status_note() {
		$this->status = array(
			array(
				'name'             => __( 'In progress', 'frais-pro' ),
				'old_slug'         => 'En cours',
				'is_default'       => true,
				'special_behavior' => '',
				'color'            => '#898de5',
			),
			array(
				'name'             => __( 'Validated', 'frais-pro' ),
				'old_slug'         => 'Validée',
				'is_default'       => false,
				'special_behavior' => '',
				'color'            => '#139bf2',
			),
			array(
				'name'             => __( 'Payed', 'frais-pro' ),
				'old_slug'         => 'Payée',
				'is_default'       => false,
				'special_behavior' => 'closed',
				'color'            => '#47e58e',
			),
			array(
				'name'             => __( 'Refused', 'frais-pro' ),
				'old_slug'         => 'Refusée',
				'is_default'       => false,
				'special_behavior' => '',
				'color'            => '#e05353',
			),
		);
	}

	/**
	 * Create default note statuses.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function create_default_statuses() {
		$this->init_status_note();

		if ( ! empty( $this->status ) ) {
			// Utilisé pour déclarer la taxonomie à l'activation du plugin. L'action "init" n'est pas lancée à ce moment là.
			$this->callback_init();

			foreach ( $this->status as $category_data ) {
				$category_slug = sanitize_title( $category_data['name'] );
				$tax           = get_term_by( 'slug', $category_slug, $this->get_type(), ARRAY_A );

				if ( ! empty( $tax['term_id'] ) && is_int( $tax['term_id'] ) ) {
					$category_data['id'] = $tax['term_id'];
				}

				$category_data['slug'] = $category_slug;

				$this->update( $category_data );
			}
		}
	}


	/**
	 * Récupères la liste des status possible pour les notes de frais
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return array (Voir au dessus).
	 */
	public function get_statuses() {
		return $this->status;
	}

}

Note_Status_Class::g();
