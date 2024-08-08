<?php

function crf_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'reservations';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        unique_id VARCHAR(50) UNIQUE NOT NULL,
        name VARCHAR(255) NOT NULL,
        phone VARCHAR(50) NOT NULL,
        date DATE NOT NULL,
        pickup_time TIME NOT NULL,
        event_type VARCHAR(255) NOT NULL,
        hours INT(11) NOT NULL,
        passengers INT(11) NOT NULL,
        customer_terms ENUM('Yes', 'No') NOT NULL,
        terms_conditions ENUM('Yes', 'No') NOT NULL,
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function crf_enqueue_scripts() {
    wp_enqueue_script('crf-script', plugin_dir_url(__FILE__) . 'js/crf-script.js', array('jquery'), '1.0', true);
    wp_localize_script('crf-script', 'crf_vars', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'crf_enqueue_scripts');
