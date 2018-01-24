<?php
/**
 * Initialise les fichiers .config.json
 *
 * @package Eoxia\Plugin
 *
 * @since 1.0.0
 * @version 1.3.0
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialise les scripts JS et CSS du Plugin
 * Ainsi que le fichier MO
 */
class Note_De_Frais_Action {

	/**
	 * Le constructeur ajoutes les actions WordPress suivantes:
	 * admin_enqueue_scripts (Pour appeller les scripts JS et CSS dans l'admin)
	 * admin_print_scripts (Pour appeler les scripts JS en bas du footer)
	 * plugins_loaded (Pour appeler le domaine de traduction)
	 */
	public function __construct() {
		// Initialises ses actions que si nous sommes sur une des pages réglés dans le fichier digirisk.config.json dans la clé "insert_scripts_pages".
		$page = ( ! empty( $_REQUEST['page'] ) ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		$post = ( ! empty( $_REQUEST['post'] ) ) ? intval( $_REQUEST['post'] ) : '';

		if ( in_array( $page, \eoxia\Config_Util::$init['frais-pro']->insert_scripts_pages_css, true ) && empty( $post ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts_css' ), 11 );
		}

		if ( in_array( $page, \eoxia\Config_Util::$init['frais-pro']->insert_scripts_pages_js, true ) && empty( $post ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_before_admin_enqueue_scripts_js' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts_js' ), 11 );
		}

		add_action( 'init', array( $this, 'callback_plugins_loaded' ) );
		add_action( 'admin_init', array( $this, 'callback_admin_init' ) );
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 12 );
	}

	/**
	 * Initialise les fichiers JS inclus dans WordPress (jQuery, wp.media et thickbox)
	 *
	 * @return void nothing
	 *
	 * @since 1.0
	 * @version 6.2.5.0
	 */
	public function callback_before_admin_enqueue_scripts_js() {
		wp_enqueue_media();
		add_thickbox();
	}

	/**
	 * Initialise le fichier style.min.css et backend.min.js du plugin DigiRisk.
	 *
	 * @return void nothing
	 *
	 * @since 1.0
	 * @version 1.3.0
	 */
	public function callback_admin_enqueue_scripts_js() {
		wp_enqueue_script( 'frais-pro-script', PLUGIN_NOTE_DE_FRAIS_URL . 'core/assets/js/backend.min.js', array( 'jquery' ), \eoxia\Config_Util::$init['frais-pro']->version, false );
		wp_localize_script( 'frais-pro-script', 'noteDeFrais', array(
			'confirmMarkAsPayed' => __( 'Are you sur you want to mark as payed? You won\'t be able to change anything after this action.', 'frais-pro' ),
		) );
		wp_enqueue_script( 'frais-pro-script-datetimepicker-script', PLUGIN_NOTE_DE_FRAIS_URL . 'core/assets/js/jquery.datetimepicker.full.js', array(), \eoxia\Config_Util::$init['frais-pro']->version );
	}

	/**
	 * Initialise le fichier style.min.css et backend.min.js du plugin DigiRisk.
	 *
	 * @return void nothing
	 *
	 * @since 1.0
	 * @version 1.3.0
	 */
	public function callback_admin_enqueue_scripts_css() {
		wp_register_style( 'frais-pro-style', PLUGIN_NOTE_DE_FRAIS_URL . 'core/assets/css/style.css', array(), \eoxia\Config_Util::$init['frais-pro']->version );
		wp_enqueue_style( 'frais-pro-style' );

		wp_enqueue_style( 'frais-pro-datepicker', PLUGIN_NOTE_DE_FRAIS_URL . 'core/assets/css/jquery.datetimepicker.css', array(), \eoxia\Config_Util::$init['frais-pro']->version );
	}

	/**
	 * Initialise le fichier MO
	 *
	 * @since 1.0
	 * @version 1.2.0
	 */
	public function callback_plugins_loaded() {
		register_post_status( 'archive', array(
			'label'                     => 'Archive',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
		) );
		load_plugin_textdomain( 'frais-pro', false, PLUGIN_NOTE_DE_FRAIS_DIR . '/core/assets/languages/' );
	}

	/**
	 * Installes les données par défaut.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @return void
	 */
	public function callback_admin_init() {
		$core_option = get_option( \eoxia\Config_Util::$init['frais-pro']->core_option, array(
			'db_version' => '',
		) );

		if ( empty( $core_option['db_version'] ) ) {
			$file_content = file_get_contents( \eoxia\Config_Util::$init['frais-pro']->core->path . 'assets/json/default.json' );
			$data = json_decode( $file_content, true );

			if ( ! empty( $data ) ) {
				foreach ( $data as $category ) {
					$category_slug = sanitize_title( $category['category_id'] . ' : ' . $category['name'] );
					$tax = get_term_by( 'slug', $category_slug, Type_Note_Class::g()->get_taxonomy(), ARRAY_A );

					if ( ! empty( $tax['term_id'] ) && is_int( $tax['term_id'] ) ) {
						$category['id'] = $tax['term_id'];
					}
					$category['slug'] = $category_slug;

					Type_Note_Class::g()->update( $category );
				}
			}

			$core_option['db_version'] = str_replace( '.', '', \eoxia\Config_Util::$init['frais-pro']->version );
			update_option( \eoxia\Config_Util::$init['frais-pro']->core_option, $core_option );
		}

	}

	/**
	 * Définition du menu dans l'administration de wordpress pour Digirisk / Define the menu for wordpress administration
	 *
	 * @since 1.0.0
	 * @version 1.3.0
	 */
	public function callback_admin_menu() {
		add_menu_page( __( 'Frais.pro', 'frais-pro' ), __( 'Frais.pro', 'frais-pro' ), 'manage_options', 'frais-pro', array( Note_De_Frais_Class::g(), 'display' ), 'dashicons-format-aside' );
		add_submenu_page( 'frais-pro', __( 'Frais.pro - Notes', 'frais-pro' ), __( 'Notes', 'frais-pro' ), 'manage_options', 'frais-pro', array( Note_De_Frais_Class::g(), 'display' ) );
	}

}

new Note_De_Frais_Action();
