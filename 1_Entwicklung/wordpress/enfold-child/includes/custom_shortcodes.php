<?php

// Shortcode for custom Elements
/* Now add a new folder in your child theme directory called shortcodes.
 * If you copy an element from enfold>config-templatebuilder>avia-shortcodes to this folder it will replace the one in the parent and be used instead.
 * You can also add new ones using the other shortcode elements as examples.
*/
add_filter('avia_load_shortcodes', 'avia_include_shortcode_template', 15, 1);
function avia_include_shortcode_template($paths)
{
    $template_url = get_stylesheet_directory();
    array_unshift($paths, $template_url . '/shortcodes/');

    return $paths;
}
