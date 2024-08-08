<?php

class CRF_Admin_Settings {

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_admin_menu'));
    }

    public static function add_admin_menu() {
        add_menu_page(
            'Custom Reservation Form Settings',
            'CRF Settings',
            'manage_options',
            'crf-settings',
            array(__CLASS__, 'settings_page')
        );
    }

    public static function settings_page() {
        ?>
        <div class="wrap">
            <h1>Custom Reservation Form Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('crf-settings-group');
                do_settings_sections('crf-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
