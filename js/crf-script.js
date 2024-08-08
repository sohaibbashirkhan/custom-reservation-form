jQuery(document).ready(function($) {
    $('#crf-reservation-form').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: crf_vars.ajax_url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    alert('Form submitted successfully!');
                    $('#crf-reservation-form')[0].reset();
                } else {
                    alert('An error occurred.');
                }
            }
        });
    });
});
