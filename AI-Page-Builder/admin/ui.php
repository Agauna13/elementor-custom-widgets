<?php
/**
 * UI Class
 *
 * Maneja la creación del menú de administración y la carga de vistas del admin.
 *
 * @package AIPageBuilder\Admin
 */

namespace AIPageBuilder\Admin;

// Evitar acceso directo.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UI
 */
class UI {

	/**
	 * Registra el menú de administración.
	 */
	public function register_menu() {
		add_menu_page(
			'AI Page Builder',           // Título de la página
			'AI Page Builder',           // Título del menú
			'manage_options',            // Capacidad requerida
			'ai-page-builder',           // Slug del menú
			[ $this, 'render_blocks_page' ], // Función de callback
			'dashicons-layout',          // Icono
			20                           // Posición
		);
	}

	/**
	 * Encola estilos del admin.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'aipb-admin-css', AIPB_URL . 'assets/css/global.css', [], AIPB_VERSION );
	}

	/**
	 * Renderiza la página de gestión de bloques.
	 */
	public function render_blocks_page() {
		// Verificar permisos.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Incluir el archivo de la vista.
		// La lógica de procesamiento de formularios (POST) también podría ir aquí o en el archivo incluido.
		// Para mantener limpieza, delegamos al manager si hay POST, y luego mostramos vista.
		
		$block_manager = new \AIPageBuilder\Includes\BlockManager();
		
		$message = '';
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['aipb_upload_action'] ) ) {
			$result = $block_manager->handle_upload();
			if ( is_wp_error( $result ) ) {
				$message = '<div class="notice notice-error"><p>' . esc_html( $result->get_error_message() ) . '</p></div>';
			} else {
				$message = '<div class="notice notice-success"><p>Bloque instalado correctamente.</p></div>';
			}
		}

		// Pasar variables a la vista
		$installed_blocks = $block_manager->get_installed_blocks();
		
		// Incluir vista
		require_once AIPB_PATH . 'admin/pages/blocks.php';
	}
}
