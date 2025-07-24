jQuery(($) => {

    // Add & Remove Step on Backend
    $('#add-step').on('click', function() {

        $.ajax({
            url: AddStep_URL.ajax_url,
            type: 'POST',
            data: {
                action: 'add_step_script'
            },
            success: function(response) {
                $('#bundle-steps').append(response);
            }
        });

    });

    
    $(document).on('click', '.remove-step', function() {
        $(this).closest('.step').remove();
    });

});