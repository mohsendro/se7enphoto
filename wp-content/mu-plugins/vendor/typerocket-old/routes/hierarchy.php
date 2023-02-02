<?php
/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

$router = new MakeitWorkPress\WP_Router\Router( 
    [
        'custom'    => ['route' => 'custom/', 'title' => __('Custom Template Title')],
        'home'      => ['route' => 'home/', 'title' => __('Home Template Title')],
        'archive'   => ['route' => 'archive/', 'title' => __('Archive Template Title')],
        'blog'      => ['route' => 'blog/', 'title' => __('Blog Template Title')],
    ], 
    WPPLUS_DIR_PATH . 'vendor/typerocket/resources/themes',    // The folder in your theme or child theme where the custom templates are stored. Use any full path to locate templates in plugins.
    'template',     // The query var by which the template is identified, in this case through get_query_var('template'). Defaults to template.
    false           // Whether to debug or not, which will output all rewrite rules on the front-end
);