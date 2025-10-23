<?php

/**
 * Debugging function to print text in a preformatted block.
 *
 * @param string $text The text to be printed.
 * @param string $title Title for the debug output.
 * @param bool $frontend Whether the debug output is for the frontend.
 */
function ti_debug($text, $title = '', $frontend = false)
{
    if (!$frontend && !is_admin()) {
        // Frontend debugging is not allowed
        return;
    }

    if (!current_user_can('manage_options')) {
        return; // Only allow admins to see debug output
    }

    if (!get_option('thammit_enable_debugging')) {
        return; // Debugging is not enabled
    }

    echo "<div class='error'><p><strong>" . $title . ":</strong> " . $text . "</p></div>";
}
