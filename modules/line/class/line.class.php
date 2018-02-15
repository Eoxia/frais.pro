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
	protected $post_type = 'fp_line';

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
	 * Fonction de callback avant d'insérer les données en mode POST.
	 *
	 * @var array
	 */
	protected $before_post_function = array( '\frais_pro\before_update_line' );


	/**
	 * Fonction de callback avant de mêttre à jour les données en mode PUT.
	 *
	 * @var array
	 */
	protected $before_put_function = array( '\frais_pro\before_update_line' );

	/**
	 * La fonction appelée automatiquement avant la création de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $after_post_function = array();

	/**
	 * La fonction appelée automatiquement avant la modification de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $after_put_function = array( '\frais_pro\after_update_line' );

	/**
	 * La fonction appelée automatiquement avant la modification de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $after_get_function = array( '\frais_pro\build_line_datas' );

	/**
	 * Le nom pour le resgister post type
	 *
	 * @var string
	 */
	protected $post_type_name = 'Note';

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
			'line'         => $line,
			'line_type_id' => ( null !== $line && ! empty( $line->taxonomy[ Line_Type_Class::g()->get_type() ][0] ) && ! empty( $line->taxonomy[ Line_Type_Class::g()->get_type() ][0]->term_id ) ? $line->taxonomy[ Line_Type_Class::g()->get_type() ][0]->term_id : 0 ),
			'mode'         => ( ( ! empty( $args ) && ! empty( $args['note_is_closed'] ) && $args['note_is_closed'] ) ? 'view' : 'edit' ),
		) );

		\eoxia\View_Util::exec( 'frais-pro', 'line', 'item', $template_args );
	}

	/**
	 * Check if a line is correctly filled in or if there are missing fields.
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

		foreach ( \eoxia\Config_Util::$init['frais-pro']->line->line_mandatory_values as $field_key => $field_details ) {
			if ( empty( $field_details ) ) {
				if ( empty( $line->$field_key ) ) {
					$line_state['status']   = false;
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
					$line_state['status']   = false;
					$line_state['errors'][] = $field_key;
				}
			}
		}

		return $line_state;
	}
}

Line_Class::g();
