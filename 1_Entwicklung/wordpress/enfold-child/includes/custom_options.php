<?php



// ThammIT Admin Menü

/** Admin-Menü hinzufügen */
add_action('admin_menu', function () {
    if (!current_user_can('manage_options')) { //Allows access to Administration Screens options
        return;
    }

    // Hauptmenü „ThammIT“
    add_menu_page(
        'ThammIT',
        'ThammIT',
        'manage_options',
        'thammit-dashboard',
        'thammit_settings_page_html',
        'dashicons-admin-tools',
        76 // Position im Menü (76, um es unter Werkzeuge anzuzeigen)
    );
});

add_action('admin_init', 'thammit_register_settings');

function thammit_register_settings()
{
    register_setting('thammit_options_group', 'thammit_enable_maillog');
    register_setting('thammit_options_group', 'thammit_enable_debugging');
    register_setting('thammit_options_group', 'thammit_disable_demoimport');
    register_setting('thammit_options_group', 'thammit_disable_comments');

    add_settings_section(
        'thammit_main_section',
        'Allgemeine Einstellungen',
        null,
        'thammit-settings'
    );

    add_settings_field(
        'thammit_enable_maillog',
        'Maillog aktivieren',
        'thammit_maillog_checkbox_html',
        'thammit-settings',
        'thammit_main_section'
    );
    add_settings_field(
        'thammit_enable_debugging',
        'Debugging aktivieren',
        'thammit_debugging_checkbox_html',
        'thammit-settings',
        'thammit_main_section'
    );
    add_settings_field(
        'thammit_disable_demoimport',
        'Demo-Import deaktivieren',
        'thammit_disable_demoimport_checkbox_html',
        'thammit-settings',
        'thammit_main_section'
    );
    add_settings_field(
        'thammit_disable_comments',
        'Kommentare vollständig deaktivieren',
        'thammit_disable_comments_checkbox_html',
        'thammit-settings',
        'thammit_main_section'
    );
}

function thammit_maillog_checkbox_html()
{
    $value = get_option('thammit_enable_maillog');
    echo '<input type="checkbox" name="thammit_enable_maillog" value="1"' . checked(1, $value, false) . '> ';
    echo '<label>Aktiviert das Protokollieren aller ausgehenden E-Mails.</label>';
}
function thammit_debugging_checkbox_html()
{
    $value = get_option('thammit_enable_debugging');
    echo '<input type="checkbox" name="thammit_enable_debugging" value="1"' . checked(1, $value, false) . '> ';
    echo '<label>Aktiviert das Debugging für die Anwendung. Dies wird nur ausgegeben, wenn der Benutzer im Backend eingeloggt ist und Admin-Rechte hat</label>';
}
function thammit_disable_demoimport_checkbox_html()
{
    $value = get_option('thammit_disable_demoimport');
    echo '<input type="checkbox" name="thammit_disable_demoimport" value="1"' . checked(1, $value, false) . '> ';
    echo '<label>Deaktiviert den Demo-Import im Enfold-Menü.</label>';
}
function thammit_disable_comments_checkbox_html()
{
    $value = get_option('thammit_disable_comments');
    echo '<input type="checkbox" name="thammit_disable_comments" value="1"' . checked(1, $value, false) . '> ';
    echo '<label>Deaktiviert die Kommentare auf der Seite vollständig.</label>';
}

function thammit_settings_page_html()
{
?>
    <div class="wrap">
        <h1>ThammIT Einstellungen</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('thammit_options_group');
            do_settings_sections('thammit-settings');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

// Debug am Anfang, falls es hier schon genutzt werden soll.
include_once('debug.php');
// Abfrage der Optionen und entsprechende Includes ausführen
if (get_option('thammit_enable_maillog') == 1) {
    // Maillog ist aktiv – hier Logging ausführen
    include_once('maillog.php');
}

/* Demo-Daten Import im Enfold-Menü ausblenden. Aktivieren, sobald der Import gemacht ist und der Punkt ausgeblendet werden soll, damit nich später jemand Demo-Daten importiert und damit alles überschreibt. */
if (get_option('thammit_disable_demoimport') == 1) {
    add_action('admin_head', 'hide_theme_options_tab');
    function hide_theme_options_tab()
    {
        echo '
        <style>
       	.avia_section_header.goto_demo,	.avia_subpage_container#avia_demo
            {display:none !important;}
        </style>
    ';
    }
}


if (get_option('thammit_disable_comments') == 1) {
    // Maillog ist aktiv – hier Logging ausführen
    include_once('disable_comments.php');
}
