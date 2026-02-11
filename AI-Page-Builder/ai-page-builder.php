<?php
/**
 * Plugin Name: AI Page Builder
 * Plugin URI:  https://antigravity.dev
 * Description: Sistema de bloques HTML generados por IA, independientes del tema y persistentes.
 * Version:     1.0.0
 * Author:      Alan Adamson
 * Author URI:  https://antigravity.dev
 * Text Domain: ai-page-builder
 * Domain Path: /languages
 *
 * @package    AIPageBuilder
 */

// Si este archivo es llamado directamente, abortar.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Constantes del Plugin.
 */
define( 'AIPB_VERSION', '1.0.0' );
define( 'AIPB_PATH', plugin_dir_path( __FILE__ ) );
define( 'AIPB_URL', plugin_dir_url( __FILE__ ) );
define( 'AIPB_UPLOADS_DIR', wp_upload_dir()['basedir'] . '/ai-page-builder' );
define( 'AIPB_UPLOADS_URL', wp_upload_dir()['baseurl'] . '/ai-page-builder' );

/**
 * Autoloader y carga de dependencias.
 */
require_once AIPB_PATH . 'loader.php';

/**
 * Iniciar la ejecución del plugin.
 *
 * @since 1.0.0
 */
function run_ai_page_builder() {
	$plugin = new AIPageBuilder\Loader();
	$plugin->run();
}
run_ai_page_builder();
