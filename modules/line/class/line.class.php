<?php
/**
 * Classe gérant les lignes des notes de frais
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
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
	protected $type = 'fp_line';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key = 'fp_line';

	/**
	 * Slug de base pour la route dans l'api rest
	 *
	 * @var string
	 */
	protected $base = 'line';

	/**
	 * Le préfixe de la note
	 *
	 * @var string
	 */
	public $element_prefix = 'L';

	protected $callback_func = array(
		'before_put'  => array( '\frais_pro\before_update_line' ),
		'before_post' => array( '\frais_pro\before_get_identifier', '\frais_pro\before_update_line' ),
		'after_get'   => array( '\frais_pro\after_get_line' ),
		'after_put'   => array( '\frais_pro\after_update_line' ),
	);

	/**
	 * Le nom pour le resgister post type
	 *
	 * @var string
	 */
	protected $post_type_name = 'Line';

	/**
	 * Display a line.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param Line_Model $line The full line model.
	 * @param array      $args Optionnal. Optionnal arguments to pass through display.
	 *
	 * @return void
	 */
	public function display( $line, $args = array() ) {
		$template_args = wp_parse_args( $args, array(
			'line'           => $line,
			'line_type_id'   => ( null !== $line && ! empty( $line->data['current_category'] ) && ! empty( $line->data['current_category']->data['id'] ) ? $line->data['current_category']->data['id'] : 0 ),
			'mode'           => ( ( ! empty( $args ) && ! empty( $args['note_is_closed'] ) && $args['note_is_closed'] ) ? 'view' : 'edit' ),
			'note_is_closed' => ( ! empty( $args ) && isset( $args['note_is_closed'] ) ? (bool) $args['note_is_closed'] : false ), // Voir Alex si c'est de la merde.
		) );

		\eoxia\View_Util::exec( 'frais-pro', 'line', 'item', $template_args );
	}

	/**
	 * Check if a line is correctly filled in or if there are missing fields.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param  array|object $line Current line to check if all values are correctly setted.
	 *
	 * @return array                      Line status with details if there are errors.
	 */
	public function check_line_status( $line ) {
		$line_state = array(
			'status' => true,
			'errors' => array(),
		);

		$line_schema = $this->get_schema();

		foreach ( \eoxia\Config_Util::$init['frais-pro']->line->line_required_values->entries as $field_key ) {
			if ( empty( $line->data[ $field_key ] ) ) {
				if ( in_array( $field_key, \eoxia\Config_Util::$init['frais-pro']->line->amount_entries, true ) ) {
					$special_treatment   = isset( $line_schema[ $field_key ]['special_treatment'] ) ? $line_schema[ $field_key ]['special_treatment'] : '';
					$current_field_state = $this->check_amount_input_status( $line, $special_treatment );
					if ( ! $current_field_state ) {
						$line_state['status']   = false;
						$line_state['errors'][] = $field_key;
					}
				} else {
					$line_state['status']   = false;
					$line_state['errors'][] = $field_key;
				}
			}
		}

		return $line_state;
	}

	/**
	 * Vérifie si un chmaps est obligatoire pour que la ligne soit valide ou non.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param Line_Model $line        La ligne sur laquelle il faut vérifier le statut du champs.
	 * @param string     $field       Le nom du champs à vérifier.
	 * @param boolen     $note_status Le statut de la note: note fermée si true, note ouverte si false.
	 *
	 * @return boolean                Les classes a appliquer sur le conteneur du champs.
	 */
	public function check_field_status( $line, $field, $note_status = false ) {
		$line_custom_class = array();

		$line_schema = $this->get_schema();

		foreach ( \eoxia\Config_Util::$init['frais-pro']->line->line_required_values->entries as $field_key ) {
			if ( $field === $field_key ) {
				$current_field_state = false;
				if ( in_array( $field_key, \eoxia\Config_Util::$init['frais-pro']->line->amount_entries, true ) ) {
					$special_treatment   = isset( $line_schema[ $field_key ]['special_treatment'] ) ? $line_schema[ $field_key ]['special_treatment'] : '';
					$current_field_state = $this->check_amount_input_status( $line, $special_treatment );
				}

				$line_custom_class[] = 'input-is-required';
				if ( empty( $line->data[ $field_key ] ) && ! $current_field_state ) {
					$line_custom_class[] = 'input-error';
				} elseif ( $current_field_state || $note_status ) {
					$line_custom_class[] = 'form-element-disable';
				}
			}
		}

		if ( ! in_array( $field, \eoxia\Config_Util::$init['frais-pro']->line->line_required_values->entries, true ) && $note_status ) {
			$line_custom_class[] = 'form-element-disable';
		}

		return implode( ' ', $line_custom_class );
	}

	/**
	 * Vérifie si un champs est obligatoire pour que la ligne soit valide ou non.
	 *
	 * Fonctionnement:
	 * 1- A la création tous les champs sont en lecture seul. On change leur état au moment du choix de la catégorie
	 * 2- Si la catégorie a déjà était choisie:
	 *  2.a- Si la catégorie choisie nécessite un traitement special et que le paramètre $special_treatment est égal à cette valeur alors ce champs n'est pas en lecture seule.
	 *  2.b- Si la catégorie choisie nécessite un traitement special et que le paramètre $special_treatment est différent de cette valeur, alors le champs est en lecture seule.
	 *  2.c- Si la catégorie choisie ne nécessite pas de traitement special et que le paramètre $special_treatment est défini alors le champs est en lecture seule.
	 *  2.d- Si la catégorie choisie ne nécessite pas de traitement special et que le paramètre $special_treatment est vide alors le champs n'est pas en lecture seule.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param Line_Model $line              La ligne sur laquelle il faut vérifier le statut du champs.
	 * @param string     $special_treatment Le traitement a appliquer sur le champs si il en nécessite.
	 *
	 * @return boolean        Le statut obligatoire ou non du champs.
	 */
	public function check_amount_input_status( $line, $special_treatment = '' ) {
		$is_read_only = true;

		// Si aucune catégorie alors les champs restent en lecture seule. Sinon va vérifier la ligne.
		if ( ! empty( $line->data['current_category'] ) ) {
			// Le champs actuel ne nécessite pas de traitement spécial et la catégorie choisie n'a pas de traitement special.
			if ( empty( $special_treatment ) && empty( $line->data['current_category']->data['special_treatment'] ) ) {
				$is_read_only = false;
			}

			// Le champs actuel nécessite un traitement spécial, la catégorie nécessite un traitement spécial et il s'agit du même alors le champs n'est plus en lecture seule.
			if ( ! empty( $special_treatment ) && ! empty( $line->data['current_category']->data['special_treatment'] ) && ( $special_treatment === $line->data['current_category']->data['special_treatment'] ) ) {
				$is_read_only = false;
			}
		}

		return $is_read_only;
	}

}

Line_Class::g();
