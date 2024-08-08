<?php
/**
 * Plugin Name: Custom Reservation Form
 * Description: A plugin to add a custom reservation form and handle submissions.
 * Version: 1.0
 * Author: Your Name
 * License: GPL2
 */

// Prevent direct access
defined('ABSPATH') or die('No script kiddies please!');

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/class-crf-form-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-crf-admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/crf-functions.php';

// Register activation hook
register_activation_hook(__FILE__, array('CRF_Form_Handler', 'activate'));

// Register deactivation hook
register_deactivation_hook(__FILE__, array('CRF_Form_Handler', 'deactivate'));

// Initialize the plugin
function crf_init() {
    CRF_Form_Handler::init();
    CRF_Admin_Settings::init();
}
add_action('plugins_loaded', 'crf_init');
