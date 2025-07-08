jQuery(($) => {

    // Adicionar nova etapa
    $('#add-step').on('click', function() {

        console.log(wc_addstep_ajax.ajax_url);

        $.ajax({
            url: wc_addstep_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'add_step_script'
            },
            success: function(response) {
                $('#bundle-steps').append(response);
            }
        });

    });

    // Remover etapa
    $(document).on('click', '.remove-step', function() {
        $(this).closest('.step').remove();
    });

});