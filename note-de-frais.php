<?php
/**
 * Fichier boot du plugin
 *
 * @package Eoxia\Plugin
 */

namespace note_de_frais;
/**
 * Plugin Name: Note de frais
 * Plugin URI:  http://www.evarisk.com/document-unique-logiciel
 * Description: Un super module de notes de frais.
 * Version:     1.0.0-alpha
 * Author:      Eoxia
 * Author URI:  http://www.evarisk.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /core/assets/languages
 * Text Domain: note-de-frais
 */

DEFINE( 'PLUGIN_NOTE_DE_FRAIS_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'PLUGIN_NOTE_DE_FRAIS_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'PLUGIN_NOTE_DE_FRAIS_DIR', basename( __DIR__ ) );

require_once 'core/external/wpeo_util/singleton.util.php';
require_once 'core/external/wpeo_util/init.util.php';
require_once 'core/external/wpeo_log/class/log.class.php';

\eoxia\Init_Util::g()->exec( PLUGIN_NOTE_DE_FRAIS_PATH, basename( __FILE__, '.php' ) );
