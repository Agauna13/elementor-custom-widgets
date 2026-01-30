<?php
// Functions to load the scroll menu widget

function register_scroll_menu_widget( $widgets_manager ) {
	require_once( __DIR__ . '/scroll-menu.php' );
	$widgets_manager->register( new \Scroll_Menu_Widget() );
}
add_action( 'elementor/widgets/register', 'register_scroll_menu_widget' );

function enqueue_scroll_menu_assets() {
	wp_enqueue_script( 'scroll-menu-js', plugins_url( '/assets/js/scroll-menu.js', __FILE__ ), [ 'jquery', 'elementor-frontend' ], '1.0.0', true );
	wp_enqueue_style( 'scroll-menu-css', plugins_url( '/assets/css/scroll-menu.css', __FILE__ ), [], '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_scroll_menu_assets' );
