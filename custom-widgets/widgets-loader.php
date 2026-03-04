<?php
namespace AG_Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Widgets_Loader {

	private static $_instance = null;
	
	// Almacena las clases de widgets descubiertas para evitar duplicidades
	private $discovered_widgets = [];

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		// Registrar Widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

		// Registrar Styles (CSS)
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );

		// Registrar Scripts (JS)
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );
	}

	/**
	 * Devuelve el handle correcto basado en el nombre del archivo.
	 * Convención: ag-widget-{basename_sin_extension}
	 */
	public static function asset_handle( $filename ) {
		$basename = pathinfo( $filename, PATHINFO_FILENAME );
		return 'ag-widget-' . $basename;
	}

	/**
	 * Calcula de forma segura la URI base del directorio 'custom-widgets'
	 * Funciona tanto si está en Child Theme, Parent Theme o Plugin.
	 */
	private function get_base_uri() {
		$file_path      = wp_normalize_path( __DIR__ );
		$wp_content_dir = wp_normalize_path( WP_CONTENT_DIR );
		
		if ( strpos( $file_path, $wp_content_dir ) === 0 ) {
			$relative_path = str_replace( $wp_content_dir, '', $file_path );
			return content_url( $relative_path );
		}
		
		// Fallback por defecto asumiendo que está en el root del theme
		return get_stylesheet_directory_uri() . '/custom-widgets';
	}

	/**
	 * B) Registrar Widgets
	 */
	public function register_widgets( $widgets_manager ) {
		$widgets_dir = __DIR__ . '/widgets/';

		if ( ! is_dir( $widgets_dir ) ) {
			return;
		}

		$files = glob( $widgets_dir . '*.php' );
		if ( ! $files ) {
			return; // No hay widgets
		}

		foreach ( $files as $file ) {
			// Guardar estado de clases antes y después para detectar las nuevas
			$classes_before = get_declared_classes();
			
			require_once $file;
			
			$classes_after = get_declared_classes();
			$new_classes   = array_diff( $classes_after, $classes_before );

			foreach ( $new_classes as $new_class ) {
				// Evitar registro duplicado en caso de reinclusión u otros fallos
				if ( in_array( $new_class, $this->discovered_widgets ) ) {
					continue;
				}

				// Solo registrar si extiende de \Elementor\Widget_Base
				if ( is_subclass_of( $new_class, '\Elementor\Widget_Base' ) ) {
					$widgets_manager->register( new $new_class() );
					$this->discovered_widgets[] = $new_class;
				}
			}
		}
	}

	/**
	 * C) Registrar Styles (.css)
	 */
	public function register_styles() {
		$css_dir = __DIR__ . '/assets/css/';
		if ( ! is_dir( $css_dir ) ) {
			return;
		}

		$files = glob( $css_dir . '*.css' );
		if ( ! $files ) {
			return;
		}

		$base_uri = $this->get_base_uri();

		foreach ( $files as $file ) {
			$handle   = self::asset_handle( $file );
			$file_url = $base_uri . '/assets/css/' . basename( $file );
			
			// Usar filemtime como versión asegura que la caché se rompe
			// JUSTO en el momento exacto en que modificas el archivo.
			$version  = filemtime( $file );
			
			wp_register_style(
				$handle,
				$file_url,
				[], // Sin dependencias obligatorias por defecto en CSS
				$version
			);
		}
	}

	/**
	 * C) Registrar Scripts (.js)
	 */
	public function register_scripts() {
		$js_dir = __DIR__ . '/assets/js/';
		if ( ! is_dir( $js_dir ) ) {
			return;
		}

		$files = glob( $js_dir . '*.js' );
		if ( ! $files ) {
			return;
		}

		$base_uri = $this->get_base_uri();

		foreach ( $files as $file ) {
			$handle   = self::asset_handle( $file );
			$file_url = $base_uri . '/assets/js/' . basename( $file );
			$version  = filemtime( $file );
			
			wp_register_script(
				$handle,
				$file_url,
				[ 'jquery', 'elementor-frontend' ], // Dependencias de Elementor Obligatorias
				$version,
				true // true = cargar en el footer
			);
		}
	}
}

/**
 * A) Punto de entrada seguro.
 * Solo arranca el loader si Elementor está cargado explícitamente y dispara la acción loaded.
 * Evita Fatal Errors si Elementor está desactivado.
 */
add_action( 'elementor/loaded', function() {
	Widgets_Loader::instance();
} );
