jQuery( window ).on( 'elementor/frontend/init', function() {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/scroll_menu.default', function( $scope ) {
		var $widget = $scope.find( '.scroll-menu-widget' );
        var offset = $widget.data('scroll-offset') || 50;
        
		if ( ! $widget.length ) {
			return;
		}

		$( window ).on( 'scroll', function() {
			if ( $( window ).scrollTop() > offset ) {
				$widget.addClass( 'is-scrolled' );
			} else {
				$widget.removeClass( 'is-scrolled' );
			}
		} );
        
        // Trigger once on load in case we reload down the page
        if ( $( window ).scrollTop() > offset ) {
            $widget.addClass( 'is-scrolled' );
        }
	} );
} );
