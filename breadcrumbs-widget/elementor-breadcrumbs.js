/**
 * Elementor Breadcrumbs JS
 */
jQuery( window ).on( 'elementor/frontend/init', function() {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/elementor_breadcrumbs.default', function( $scope, $ ) {
		// Future interactive functionality can go here
		console.log( 'Elementor Breadcrumbs Loaded' );
	} );
} );
