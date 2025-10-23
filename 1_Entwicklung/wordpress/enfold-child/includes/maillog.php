<?php
/* E-Mail Logging für WordPress aktivieren

    * Dieses Skript loggt alle ausgehenden E-Mails und Fehler in eine Log-Datei.
    * Die Log-Datei wird im wp-content-Verzeichnis gespeichert.
    * Aktivieren Sie das Logging über die Einstellungen im WordPress-Admin-Bereich.
*/

// Log-Dateipfad definieren (wp-content kann beschrieben werden)
define('WP_MAIL_LOG_FILE', WP_CONTENT_DIR . '/mail-log.txt');

// Erfolgreiches Versenden loggen
add_filter('wp_mail', function ($args) {
    $log = "=== E-Mail gesendet ===\n";
    $log .= "Zeit: " . date('Y-m-d H:i:s') . "\n";
    $log .= "An: " . print_r($args['to'], true) . "\n";
    $log .= "Betreff: " . $args['subject'] . "\n";
    // $log .= "Nachricht: " . $args['message'] . "\n"; // Nachricht kann sensibel sein, daher auskommentiert
    $log .= "Headers: " . print_r($args['headers'], true) . "\n";
    $log .= "=======================\n\n";
    file_put_contents(WP_MAIL_LOG_FILE, $log, FILE_APPEND);
    return $args;
});

// Fehlerhafte E-Mails loggen
add_action('wp_mail_failed', function ($wp_error) {
    $log = "=== E-Mail FEHLGESCHLAGEN ===\n";
    $log .= "Zeit: " . date('Y-m-d H:i:s') . "\n";
    $log .= "Fehler: " . $wp_error->get_error_message() . "\n";
    if ($data = $wp_error->get_error_data()) {
        $log .= "Fehlerdaten: " . print_r($data, true) . "\n";
    }
    $log .= "=============================\n\n";
    file_put_contents(WP_MAIL_LOG_FILE, $log, FILE_APPEND);
});


/** Eintrag in Admin-Menü hinzufügen */
add_action('admin_menu', function () {
    if (!current_user_can('manage_options')) { //Allows access to Administration Screens options
        return;
    }

    // Untermenü „E-Mail-Log“
    add_submenu_page(
        'thammit-dashboard',
        'E-Mail-Log',
        'E-Mail-Log',
        'manage_options',
        'thammit-email-log',
        'render_email_log_viewer_page'
    );
});

// Ausgabe im Backend
function render_email_log_viewer_page()
{
    echo '<div class="wrap">';
    echo '<h1>E-Mail-Log</h1>';

    if (file_exists(WP_MAIL_LOG_FILE)) {
        echo '<textarea readonly rows="30" style="width: 100%; font-family: monospace;">';
        echo esc_textarea(file_get_contents(WP_MAIL_LOG_FILE));
        echo '</textarea>';
    } else {
        echo '<p>Die Logdatei wurde noch nicht erstellt oder enthält keine Einträge.</p>';
    }

    echo '</div>';
}
