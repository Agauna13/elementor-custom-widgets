jQuery(document).ready(function($) {

    function initAgMasonry($widget) {
        var $container = $widget.find('.ag-masonry-widget');
        if(!$container.length) return;

        if ($container.data('ag-initialized')) return;
        $container.data('ag-initialized', true);

        var widgetId = $container.data('id');
        var settings = $container.data('settings') || {};

        var $filter = $container.find('.ag-filter');
        var $perPage = $container.find('.ag-per-page-select');
        var $grid = $container.find('.ag-grid');
        var $pagination = $container.find('.ag-pagination');

        var currentTerm = '';
        var currentPaged = 1;
        var currentPerPage = settings.posts_per_page || 0;

        function loadPosts() {
            $container.addClass('ag-loading');

            var data = {
                action: 'ag_masonry_grid',
                nonce: ag_masonry_vars.nonce,
                widget_id: widgetId,
                settings: JSON.stringify(settings),
                term: currentTerm,
                paged: currentPaged,
                posts_per_page: currentPerPage
            };

            $.post(ag_masonry_vars.ajax_url, data, function(response) {
                if(response.success) {
                    $grid.html(response.data.html_items);

                    if ($pagination.length) {
                        $pagination.html(response.data.html_pagination);
                    }
                }
                $container.removeClass('ag-loading');
            });
        }

        // Filtro AJAX
        if ($filter.length) {
            $filter.on('click', '.ag-filter-btn', function(e) {
                e.preventDefault();
                var $btn = $(this);

                $filter.find('.ag-filter-btn').removeClass('active').attr('aria-pressed', 'false');
                $btn.addClass('active').attr('aria-pressed', 'true');

                currentTerm = $btn.data('filter') || '';
                currentPaged = 1;
                loadPosts();
            });
        }

        // Selector Posts per Page
        if ($perPage.length) {
            $perPage.on('change', function() {
                currentPerPage = $(this).val();
                currentPaged = 1;
                loadPosts();
            });
        }

        // Paginacion AJAX — delegada en $container para que funcione tras actualizaciones AJAX
        $container.on('click', '.ag-page-link', function(e) {
            e.preventDefault();
            var paged = $(this).data('paged');
            if (paged) {
                currentPaged = paged;
                loadPosts();
            }
        });
    }

    // Run on normal load
    $('.elementor-widget-ag-masonry-grid').each(function() {
        initAgMasonry($(this));
    });

    // Run on Elementor editor preview
    $(window).on('elementor/frontend/init', function() {
        if (elementorFrontend && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/ag-masonry-grid.default', function($scope) {
                initAgMasonry($scope);
            });
        }
    });

});
