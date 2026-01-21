/*Muestra la plantilla que usa la página actual en el frontend*/

add_filter('template_include', function ($template) {
    if ( is_admin() ) {
        return $template;
    }
 
    echo '<div style="position:fixed;bottom:10px;left:10px;z-index:9999;background:#000;color:#0f0;padding:5px 10px;font-size:12px;">';
    echo 'Plantilla: ' . esc_html( basename($template) );
    echo '</div>';
 
    return $template;
});
