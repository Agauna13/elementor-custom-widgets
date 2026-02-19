jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/hover_service_card.default', function ($scope) {
        // JS logic if needed in the future
        // Currently the effect is handled via CSS
        console.log('Hover Service Card Widget Initialized');
    });
});
