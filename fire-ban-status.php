<?php
/*
Plugin Name: Fire Ban Status
Description: Allows the Fire Chief to set the current fire ban status.
Version: 0.2
Author: Matt Crossley
*/

add_action('admin_menu', 'fire_ban_status_menu');

function fire_ban_status_menu() {
    add_menu_page('Fire Ban Status', 'Fire Ban Status', 'manage_options', 'fire-ban-status', 'fire_ban_status_page');
}

function fire_ban_status_page() {
    if (isset($_POST['fire_ban_status_submit']) && check_admin_referer('fire_ban_status_update')) {
        update_option('fire_ban_status', sanitize_text_field($_POST['fire_ban_status']));
    }

    $current_status = get_option('fire_ban_status', 'No Ban'); // Default value is 'No Ban'
    
    echo '<form method="post" action="">';
    wp_nonce_field('fire_ban_status_update');
    echo '<label for="fire_ban_status">Select Fire Ban Status:</label>';
    echo '<select name="fire_ban_status">';
    $statuses = ['No Ban', 'Partial Ban', 'Full Ban'];
    foreach ($statuses as $status) {
        echo '<option value="' . $status . '" ' . selected($current_status, $status, false) . '>' . $status . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" name="fire_ban_status_submit" value="Update Status" />';
    echo '</form>';
}

add_shortcode('fire_ban_status', 'fire_ban_status_shortcode');

function fire_ban_status_shortcode() {
    $current_status = get_option('fire_ban_status', 'No Ban'); // Default value is 'No Ban'
    return 'Current Fire Ban Status: ' . $current_status;
}

add_action('wp_enqueue_scripts', 'fire_ban_status_styles');

function fire_ban_status_styles() {
    echo '<style>
        /* Add your styles here. For example: */
        .fire-ban-status {
            padding: 10px;
            border: 2px solid red;
            color: red;
            background-color: #ffecec;
        }
    </style>';
}

add_shortcode('fire_ban_status_frontend', 'fire_ban_status_frontend_form');

function fire_ban_status_frontend_form() {
    if (isset($_POST['fire_ban_status_submit'])) {
        update_option('fire_ban_status', sanitize_text_field($_POST['fire_ban_status']));
        echo '<div class="updated">Status updated successfully!</div>';
    }

    $current_status = get_option('fire_ban_status', 'No Ban');
    
    ob_start(); // Start output buffering
    echo '<form method="post" action="">';
    echo '<label for="fire_ban_status">Select Fire Ban Status:</label>';
    echo '<select name="fire_ban_status">';
    $statuses = ['No Ban', 'Partial Ban', 'Full Ban'];
    foreach ($statuses as $status) {
        echo '<option value="' . $status . '" ' . selected($current_status, $status, false) . '>' . $status . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" name="fire_ban_status_submit" value="Update Status" />';
    echo '</form>';
    return ob_get_clean(); // Return the buffered content
}

?>
