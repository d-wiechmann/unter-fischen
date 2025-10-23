<?php


add_action('init', 'load_custom_options', 20); // 20 = nach Enfold geladen
add_action('init', 'load_custom_shortcodes', 20);

function load_custom_options()
{
    include_once('includes/custom_options.php');
}
function load_custom_shortcodes()
{
    include_once('includes/custom_shortcodes.php');
}


/*
* Add your own functions here. You can also copy some of the theme functions into this file.
* Wordpress will use those functions instead of the original functions then.
*/
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/css/custom.css',
        array('parent-style'),
        false
    );
}

/* REST-API deaktivieren, da über diese Autoren-Kürzel einsehbar sind */
if (!is_user_logged_in()) {
    remove_action('rest_api_init', 'create_initial_rest_routes', 99);
}
