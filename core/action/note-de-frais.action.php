<?php
/**
 * Initialise les fichiers .config.json
 *
 * @package Eoxia\Plugin
 *
 * @since 1.0.0
 * @version 1.4.0
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

		if ( empty( $page ) || ( in_array( $page, \eoxia\Config_Util::$init['frais-pro']->insert_scripts_pages_js, true ) && empty( $post ) ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_before_admin_enqueue_scripts_js' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts_js' ), 11 );
		}

		add_action( 'init', array( $this, 'callback_plugins_loaded' ) );
		add_action( 'init', array( $this, 'callback_init' ), 11 );
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 12 );
	}

	/**
	 * Initialise les fichiers JS inclus dans WordPress (jQuery, wp.media et thickbox)
	 *
	 * @return void nothing
	 *
	 * @since 1.0.0
	 * @version 1.3.0
	 */
	public function callback_before_admin_enqueue_scripts_js() {
		wp_enqueue_media();
		add_thickbox();
	}

	/**
	 * Initialise le fichier style.min.css et backend.min.js du plugin Frais Pro.
	 *
	 * @return void nothing
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 */
	public function callback_admin_enqueue_scripts_js() {
		wp_enqueue_script( 'frais-pro-script', PLUGIN_NOTE_DE_FRAIS_URL . 'core/assets/js/backend.min.js', array( 'jquery' ), \eoxia\Config_Util::$init['frais-pro']->version, false );
		wp_localize_script( 'frais-pro-script', 'fraisPro', array(
			'updateDataUrlPage'        => 'admin_page_' . \eoxia\Config_Util::$init['frais-pro']->update_page_url,
			'confirmMarkAsPayed'       => __( 'Are you sur you want to mark as payed? You won\'t be able to change anything after this action.', 'frais-pro' ),
			'confirmUpdateManagerExit' => __( 'Your data are being updated. If you confirm that you want to leave this page, your data could be corrupted', 'frais-pro' ),
			'noteStatusInProgress'     => __( 'In progress', 'frais-pro' ),
			'noteStatusInValidated'    => __( 'Validated', 'frais-pro' ),
			'noteStatusInPayed'        => __( 'Payed', 'frais-pro' ),
			'noteStatusInRefused'      => __( 'Refused', 'frais-pro' ),
			'lineStatusInvalid'        => __( 'Invalid line', 'frais-pro' ),
			'lineStatusValid'          => __( 'Valid line', 'frais-pro' ),
			'loader'                   => '<img src=' . admin_url( '/images/loading.gif' ) . ' />',
			'updateInProgress'         => __( 'Update in progress...', 'frais-pro' ),
			'updateDone'               => __( 'Note saved', 'frais-pro' ),
			'lineAffectedSuccessfully' => __( 'Lines have been successfully assigned', 'frais-pro' ),
		) );
	}

	/**
	 * Initialise le fichier style.min.css et backend.min.js du plugin Frais Pro.
	 *
	 * @return void nothing
	 *
	 * @since 1.0.0
	 * @version 1.3.0
	 */
	public function callback_admin_enqueue_scripts_css() {
		wp_register_style( 'frais-pro-style', PLUGIN_NOTE_DE_FRAIS_URL . 'core/assets/css/style.css', array(), \eoxia\Config_Util::$init['frais-pro']->version );
		wp_enqueue_style( 'frais-pro-style' );
	}

	/**
	 * Initialise le fichier MO
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 */
	public function callback_plugins_loaded() {
		load_plugin_textdomain( 'frais-pro', false, PLUGIN_NOTE_DE_FRAIS_DIR . '/core/assets/languages/' );
	}

	/**
	 * Appel la méthode pour initialiser les données par défaut.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @return void
	 */
	public function callback_init() {
		Note_De_Frais_Class::g()->init_default_data();
	}

	/**
	 * Définition du menu dans l'administration de WordPress pour Frais Pro.
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 */
	public function callback_admin_menu() {
		add_menu_page( __( 'Frais.pro', 'frais-pro' ), __( 'Frais.pro', 'frais-pro' ), 'manage_options', \eoxia\Config_Util::$init['frais-pro']->slug, array( Note_De_Frais_Class::g(), 'display' ), 'dashicons-format-aside' );
		add_submenu_page( \eoxia\Config_Util::$init['frais-pro']->slug, __( 'Frais.pro - Notes', 'frais-pro' ), __( 'Notes', 'frais-pro' ), 'manage_options', \eoxia\Config_Util::$init['frais-pro']->slug, array( Note_De_Frais_Class::g(), 'display' ) );
		add_submenu_page( \eoxia\Config_Util::$init['frais-pro']->menu_edit_parent_slug, __( 'Frais.pro', 'frais-pro' ), __( 'Frais.pro', 'frais-pro' ), 'manage_options', \eoxia\Config_Util::$init['frais-pro']->slug . '-edit', array( Note_De_Frais_Class::g(), 'display' ) );
	}

}

new Note_De_Frais_Action();
