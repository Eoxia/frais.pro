<?php
/**
 * Plugin Name: Frais.pro
 * Plugin URI:  http://www.eoxia.com/
 * Description: Manage your professional fees from your WordPress website and never search for what you spent.
 * Version:     1.3.0
 * Author:      Eoxia
 * Author URI:  https://www.eoxia.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /core/assets/languages
 * Text Domain: frais-pro
 *
 * @package Note De Frais
 */

namespace frais_pro;

DEFINE( 'PLUGIN_NOTE_DE_FRAIS_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'PLUGIN_NOTE_DE_FRAIS_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'PLUGIN_NOTE_DE_FRAIS_DIR', basename( __DIR__ ) );

require_once 'core/external/eo-framework/eo-framework.php';

\eoxia\Init_Util::g()->exec( PLUGIN_NOTE_DE_FRAIS_PATH, basename( __FILE__, '.php' ) );
