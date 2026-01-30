<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Widgets Loader for Child Theme
 */
class Child_Theme_Widgets_Loader {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );
	}

	public function register_widgets( $widgets_manager ) {
		// Include and register widgets here
		require_once __DIR__ . '/widgets/advanced-heading.php';
		$widgets_manager->register( new \Child_Theme_Advanced_Heading_Widget() );
	}

	public function enqueue_styles() {
		wp_enqueue_style( 
			'child-theme-advanced-heading', 
			get_stylesheet_directory_uri() . '/custom-widgets/assets/css/advanced-heading.css', 
			[], 
			'1.0.0' 
		);
	}
}

Child_Theme_Widgets_Loader::instance();
