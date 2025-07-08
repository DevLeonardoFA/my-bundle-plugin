jQuery().ready(($) => {
   
    AjaxUrl = wc_bundle_ajax2.ajax_url;

    let selectedProducts = [];

    // Add product to bandle
    $(document).on('click', '.addtobundle', function() {

        // UI Manipulation
        const step = $(this).closest('.step');
        const productID = $(this).data('product-id');

        
        $(this).closest('li').toggleClass('selected');
        
        // change the button text
        if ($(this).text() != 'Remove from Bundle') {


            $(this).text('Remove from Bundle');

            if (!selectedProducts.includes(productID)) {
               selectedProducts.push(productID);
            }

            $(step).addClass('already-selected');

            addtopreview(productID);


        } else {

            $(this).text('Add to Bundle');
        
            const index = selectedProducts.indexOf(productID);
            if (index > -1) {
                selectedProducts.splice(index, 1);
            }

            $(step).removeClass('already-selected');

            removefrompreview();

        }
        
        
    });

    $(document).on('click', '.final-step-btn', function() {

        $.ajax({
            url: AjaxUrl,
            type: 'POST',
            data: {
                action: 'add_bundle_to_cart',
                products: selectedProducts,
                nonce: wc_bundle_ajax2.nonce
            },
            success: function(response) {
                window.location.href = response.data.cart_url;
            }
        });

    });



    function addtopreview(selectedProducts) {

        $.ajax({
            url: AjaxUrl,
            type: 'POST',
            data: {
                action: 'add_to_preview',
                product_id: selectedProducts,
            },
            success: function(response) {
                $('.bundle-preview .products').append(response.data.product_img);
            }
        });

    }

    function removefrompreview() {
        $('.bundle-preview .products img').last().remove(); 
    }





});