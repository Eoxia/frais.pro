<?php
/**
 * Classe helper pour les modèles.
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.0.0
 * @copyright 2015-2017
 * @package wpeo_model
 * @subpackage class
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( '\eoxia\Helper_Class' ) ) {

	/**
	 * Classe helper pour les modèles.
	 */
	class Helper_Class implements \ArrayAccess {

		/**
		 * Récupères le modèle.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @return Object le modèle.
		 */
		public function get_model() {
			return $this->schema;
		}

		public function get_class() {
			$called_class = \get_called_class();
			$called_class = explode( '\\', $called_class );

			$class = str_replace( 'model', 'class', \get_class( $this ) );
			$class = str_replace( 'Model', 'Class', $class );
			$class = str_replace( $called_class[0], '', $class );
			$class = str_replace( '\\', '', $class );
			return $class;
		}

		/**
		 * Permet de faire echo sur un objet et supprimes la définition du modèle avant l'affichage.
		 *
		 * @return string void
		 */
		public function __toString() {
			$this->delete_model_for_print( $this );
			echo '<pre>'; print_r( $this ); echo '</pre>';
			return '';
		}

		/**
		 * Supprime le modèle.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param  object $current L'objet complet.
		 */
		private function delete_model_for_print( $current ) {
			if ( ! empty( $this->model ) ) {
				unset( $this->model );
			}

			foreach ( $current as &$content ) {
				if ( is_array( $content ) ) {
					foreach ( $content as &$model ) {
						if ( ! empty( $model->model ) ) {
							unset( $model->model );
							$this->delete_model_for_print( $model );
						}
					}
				}
			}
		}

		/**
		 * Checks if a parameter is set.
		 *
		 * @since 1.0.0
		 *
		 * @param string $offset Parameter name.
		 * @return bool Whether the parameter is set.
		 */
		public function offsetExists( $offset ) {
			return isset( $this->$offset );
		}

		/**
		 * Retrieves a parameter from the request.
		 *
		 * @since 1.0.0
		 *
		 * @param string $offset Parameter name.
		 * @return mixed|null Value if set, null otherwise.
		 */
		public function offsetGet( $offset ) {
			return isset( $this->$offset ) ? $this->$offset : null;
		}

		/**
		 * Sets a parameter on the request.
		 *
		 * @since 1.0.0
		 *
		 * @param string $offset Parameter name.
		 * @param mixed  $value  Parameter value.
		 */
		public function offsetSet( $offset, $value ) {
			$this->$offset = $value;
		}

		/**
		 * Removes a parameter from the request.
		 *
		 * @since 1.0.0
		 *
		 * @param string $offset Parameter name.
		 */
		public function offsetUnset( $offset ) {
			unset( $this->$offset );
		}

	}

} // End if().