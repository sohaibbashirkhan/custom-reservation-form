<?php

class CRF_Form_Handler {

    public static function init() {
        add_shortcode('crf_form', array(__CLASS__, 'render_form'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
        add_action('wp_ajax_crf_submit_form', array(__CLASS__, 'handle_form_submission'));
        add_action('wp_ajax_nopriv_crf_submit_form', array(__CLASS__, 'handle_form_submission'));
    }

    public static function render_form() {
        ob_start();
        ?>
        <form id="crf-reservation-form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post">
            <input type="hidden" name="action" value="crf_submit_form">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required placeholder="Enter Your Name">
            
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" required placeholder="Enter Your Phone Number">

            <label for="date">Date</label>
            <input type="date" id="date" name="date" required>

            <label for="pickup_time">Pick-Up Time</label>
            <input type="time" id="pickup_time" name="pickup_time" required>

            <label for="event_type">Type Of Events</label>
            <select id="event_type" name="event_type" required>
                <option value="airport_transportation">Airport Transportation</option>
            </select>

            <label for="hours">Hours</label>
            <select id="hours" name="hours" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>

            <label for="passengers">Passengers</label>
            <select id="passengers" name="passengers" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>

            <fieldset>
                <legend>Customer Terms</legend>
                <label>
                    <input type="checkbox" name="customer_terms" required>
                    I agree to the Customer Terms
                </label>
            </fieldset>

            <fieldset>
                <legend>Terms And Conditions & Privacy Policies</legend>
                <label>
                    <input type="checkbox" name="terms_conditions" required>
                    I agree to the Terms and Conditions and Privacy Policies
                </label>
            </fieldset>

            <label for="notes">Additional Notes</label>
            <textarea id="notes" name="notes" rows="4" placeholder="Additional Notes"></textarea>

            <button type="submit">Pay Now</button>
        </form>
        <?php
        return ob_get_clean();
    }

    public static function handle_form_submission() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'reservations';

        $name = sanitize_text_field($_POST['name']);
        $phone = sanitize_text_field($_POST['phone']);
        $date = sanitize_text_field($_POST['date']);
        $pickup_time = sanitize_text_field($_POST['pickup_time']);
        $event_type = sanitize_text_field($_POST['event_type']);
        $hours = intval($_POST['hours']);
        $passengers = intval($_POST['passengers']);
        $customer_terms = isset($_POST['customer_terms']) ? 'Yes' : 'No';
        $terms_conditions = isset($_POST['terms_conditions']) ? 'Yes' : 'No';
        $notes = sanitize_textarea_field($_POST['notes']);

        // Generate a unique ID
        $unique_id = uniqid('res_', true);

        $wpdb->insert(
            $table_name,
            array(
                'unique_id' => $unique_id,
                'name' => $name,
                'phone' => $phone,
                'date' => $date,
                'pickup_time' => $pickup_time,
                'event_type' => $event_type,
                'hours' => $hours,
                'passengers' => $passengers,
                'customer_terms' => $customer_terms,
                'terms_conditions' => $terms_conditions,
                'notes' => $notes
            )
        );

        $to = 'your_email@example.com';
        $subject = 'New Reservation Submission';
        $message = "Unique ID: $unique_id\nName: $name\nPhone: $phone\nDate: $date\nPick-Up Time: $pickup_time\nEvent Type: $event_type\nHours: $hours\nPassengers: $passengers\nCustomer Terms: $customer_terms\nTerms and Conditions: $terms_conditions\nNotes: $notes";
        $headers = 'From: no-reply@example.com';

        wp_mail($to, $subject, $message, $headers);

        wp_send_json_success('Form submitted successfully!');
    }

    public static function enqueue_scripts() {
        wp_enqueue_script('crf-script', plugin_dir_url(__FILE__) . 'js/crf-script.js', array('jquery'), '1.0', true);
        wp_localize_script('crf-script', 'crf_vars', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }

    public static function activate() {
        crf_create_table();
    }

    public static function deactivate() {
        // Code to run on plugin deactivation (optional)
    }
}
