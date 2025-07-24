jQuery().ready(($) => {

    const wizard = $('.wc-bundle-wizard');
    const AjaxUrl = Bundle_URL.ajax_url;
    if (!wizard.length) return;

    $(wizard).removeClass('loading');

    let progressbar = wizard.find('.progressbar');
    let parts = $(progressbar).data('parts');
    let piece = Math.floor(100 / (parts - 1));
    let progressbartotal = 100;
    var toRemove = 0;

    let dots = $(progressbar).find('.dots');
    $(dots).find('.dot').first().addClass('active');
    let dotsIndex = 0;

    const popup_msg = wizard.find('.popup_msg');

    const lwh = [0, 0, 0];


    // Navegação
    $(wizard).on('click', '.next-step', function() {

        const currentStep = $(this).closest('.step');
        const nextIndex = currentStep.next();
        const selectedProduct = currentStep.find('.selected');

        lwh[0] = $(selectedProduct).data('product-length');
        lwh[1] = $(selectedProduct).data('product-width');
        lwh[2] = $(selectedProduct).data('product-height');

        if( !currentStep.hasClass('final-step') ){
            toRemove = Math.floor(toRemove + piece);
            if( !currentStep.hasClass('already-selected') ) {
                $(popup_msg).find('p').text('Please select a product before proceeding');
                $(popup_msg).fadeIn(1000);
                return;
            }
        }
        
        if( nextIndex.hasClass('final-step') ) {
            toRemove = 100;
        }
        
        var nextIndex_value = nextIndex.data('step-index');

        // se for o penultimo mudar data-is-last para true
        if (nextIndex_value === wizard.find('.step').last().data('step-index')) {
            nextIndex.find('.next-step').data('is-last', 'true');
        }

        currentStep.hide();
        $(nextIndex).show();

        loadStepProducts(nextIndex_value);

        // progressbar
        $(progressbar).find('.line').css('background-position', `${progressbartotal - toRemove}% 0%`);
        
        var prevIndex_value = $(this).data('index');
        dots.find('.dot').eq(prevIndex_value + 1).addClass('active');


    });

    $(wizard).on('click', '.prev-step', function() {

        const currentStep = $(this).closest('.step');
        const prevIndex = currentStep.prev();
        var prevIndex_value = $(this).data('index');

        if( currentStep.hasClass('already-selected') ) {
            $(popup_msg).find('p').text('Please unselect a product before proceeding');
            $(popup_msg).fadeIn(1000);
            return;
        }
        
        toRemove = Math.floor(toRemove - piece);

        currentStep.hide();
        $(prevIndex).show();

        // progressbar
        $(progressbar).find('.line').css('background-position', `${progressbartotal - toRemove}% 0%`);


        dots.find('.dot').eq(prevIndex_value).removeClass('active');


    });


    $(wizard).on('click', '.close-popup', function() {
        $(popup_msg).fadeOut(1000);
        setTimeout(() => {
            $(popup_msg).find('p').text('');
        }, 1020);
    });


    // Carregar produtos via AJAX
    function loadStepProducts(stepIndex) {

        const $step = wizard.find(`.step[data-step-index="${stepIndex}"]`);
        const $container = $step.find('.products-container');
        const stepSlug = $container.data('step-slug');
        const bundleId = wizard.data('bundle-id');
        $container.html('<p> Loading products... </p>');

        $.ajax({
            url: AjaxUrl,
            type: 'POST',
            data: {
                action: 'LoadProducts_FrontEnd',
                step_slug: stepSlug,
                bundle_id: bundleId,
                lwh: lwh,
                step_index: stepIndex
            },
            success: function(response) {
                $container.html(response);
            }
        });
    }



    
});