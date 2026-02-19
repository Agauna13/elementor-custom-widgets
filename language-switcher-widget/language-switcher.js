(function ($) {

    const LanguageSwitcherHandler = function ($scope) {

        const $wrapper = $scope.find('.elementor-language-switcher-wrapper');
        const $button = $wrapper.find('.els-button');
        const $dropdown = $wrapper.find('.els-dropdown');

        if (!$wrapper.length) return;

        function closeDropdown() {
            $wrapper.removeClass('open');
            $button.attr('aria-expanded', false);
            $dropdown.attr('aria-hidden', true);
        }

        // Toggle
        $button.off('click.rwLS').on('click.rwLS', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const isOpen = $wrapper.toggleClass('open').hasClass('open');
            $button.attr('aria-expanded', isOpen);
            $dropdown.attr('aria-hidden', !isOpen);
        });

        // Outside click
        $(document).off('click.rwLS').on('click.rwLS', function (e) {
            if (!$wrapper.is(e.target) && $wrapper.has(e.target).length === 0) {
                closeDropdown();
            }
        });

        // Close on item click
        $wrapper.find('a').off('click.rwLS').on('click.rwLS', function () {
            closeDropdown();
        });

    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/rw-language-switcher.default',
            LanguageSwitcherHandler
        );
    });

})(jQuery);
