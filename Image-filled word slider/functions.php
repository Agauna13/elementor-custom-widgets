<?php
/**
 * Text Ticker Clip Widget Integration
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Register the Text Ticker Clip Widget.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_custom_text_ticker_clip_widget( $widgets_manager ) {

	require_once( __DIR__ . '/text-ticker-clip.php' );

	if ( class_exists( '\TextTickerClip\Text_Ticker_Clip_Widget' ) ) {
		$widgets_manager->register( new \TextTickerClip\Text_Ticker_Clip_Widget() );
	}

}
add_action( 'elementor/widgets/register', 'register_custom_text_ticker_clip_widget' );

/**
 * Register Widget Styles
 */
function register_custom_text_ticker_clip_styles() {
	wp_register_style( 
		'text-ticker-clip-css', 
		plugins_url( 'assets/css/style.css', __FILE__ ), // Works if this assumes it's in a plugin structure or needs adjustment for theme
		[], 
		'1.0.0' 
	);
	
	// Fallback for theme integration: if plugins_url doesn't yield the right path because it's required in a theme
	// we might need a more robust way to get URL.
	// Allow simple overriding or assume the user puts it in the right place?
	// For "exportable", using a relative URL approach that works for both is tricky in simple PHP without knowing context.
	// But usually this logic is fine if they put it in a plugin. 
	// If in a theme, they might need get_stylesheet_directory_uri().
	// Let's try to detect or provide a variable.
}

// Improved Style Registration to handle Theme vs Plugin context
add_action( 'elementor/frontend/after_register_styles', function() {
	
	$url = '';

	// Check if we are inside a plugin or theme
	if ( strpos( __DIR__, 'plugins' ) !== false ) {
		$url = plugins_url( 'assets/css/style.css', __FILE__ );
	} else {
		// Assume theme
		// We need to find the relative path from the theme root
		$theme_path = get_stylesheet_directory();
		$relative_path = str_replace( $theme_path, '', __DIR__ );
		$url = get_stylesheet_directory_uri() . $relative_path . '/assets/css/style.css';
	}

	wp_register_style( 
		'text-ticker-clip-css', 
		$url,
		[], 
		'1.0.0' 
	);
} );
