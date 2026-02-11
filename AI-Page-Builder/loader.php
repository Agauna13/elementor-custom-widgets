<?php
/**
 * Loader class for AIPageBuilder.
 *
 * Responsable de cargar todas las dependencias y registrar los hooks.
 *
 * @package AIPageBuilder
 */

namespace AIPageBuilder;

// Evitar acceso directo.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Clase Loader.
 */
class Loader {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->load_dependencies();
	}

	/**
	 * Carga los archivos PHP necesarios.
	 */
	private function load_dependencies() {
		// Cargar lógica del Admin (UI).
		require_once AIPB_PATH . 'admin/ui.php';

		// Cargar gestor de bloques (Core).
		require_once AIPB_PATH . 'includes/BlockManager.php';
	}

	/**
	 * Ejecuta los hooks de WordPress.
	 */
	public function run() {
		// Hooks de Admin
		$admin = new Admin\UI();
		add_action( 'admin_menu', [ $admin, 'register_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $admin, 'enqueue_styles' ] );

		// Hooks del Core (Bloques)
		$block_manager = new Includes\BlockManager();
		add_shortcode( 'ai_block', [ $block_manager, 'render_shortcode' ] );
		add_action( 'init', [ $block_manager, 'init_storage' ] );
	}
}
