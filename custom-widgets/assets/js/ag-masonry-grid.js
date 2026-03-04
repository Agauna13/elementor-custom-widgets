/* 
 * JS para AG Masonry Grid
 * Handled by loader con nombre de handle: ag-widget-ag-masonry-grid
 */
(function( $ ) {
	'use strict';

	/**
	 * Manejador que se ejecuta cuando el widget de Elementor está listo en el frontend.
	 * Se usa 'frontend/element-ready/' + nombre que declaraste en get_name() de la clase + '.default'
	 * get_name() de PHP devuelve: ag_masonry_grid
	 */
	var WidgetAGMasonryGridHandler = function( $scope, $ ) {
		console.log( 'AG Masonry Grid initialized', $scope );
		
		var $wrapper = $scope.find('.ag-masonry-grid-wrapper');
		
		// Animación básica de interacción usando la API segura proporcionada (jQuery)
		$wrapper.on('mouseenter', function() {
			$(this).css({
				'box-shadow': '0 6px 15px rgba(0,0,0,0.1)',
				'transition': 'box-shadow 0.3s ease'
			});
		}).on('mouseleave', function() {
			$(this).css('box-shadow', 'none');
		});
	};

	// Asegurarse de que el widget se inicialice solo cuando Elementor Frontend esté listo
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element-ready/ag_masonry_grid.default', WidgetAGMasonryGridHandler );
	});

})( jQuery );
