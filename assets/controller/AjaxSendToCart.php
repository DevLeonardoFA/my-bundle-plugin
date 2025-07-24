<?php

// Registrar endpoint AJAX
add_action('wp_enqueue_scripts', function () {
    $base_url = admin_url('admin-ajax.php');

    $args = [
        'ajax_url' => $base_url,
        'nonce' => wp_create_nonce('mbp-nonce'),
    ];

    wp_register_script('AjaxSentToCart_Script', plugin_dir_url(__FILE__) . '/AjaxSendToCart.js', ['jquery'], '1.0.0', true);
    wp_localize_script('AjaxSentToCart_Script', 'AjaxSentToCart_URL', $args);
    wp_enqueue_script('AjaxSentToCart_Script');
});
add_action('wp_ajax_add_bundle_to_cart', 'add_bundle_to_cart');
add_action('wp_ajax_nopriv_add_bundle_to_cart', 'add_bundle_to_cart');

function add_bundle_to_cart() {

    $nonce = $_POST['nonce'];

    if (!wp_verify_nonce($nonce, 'mbp-nonce')) {
        wp_send_json_error([
            'error' => [
                'message' => __('Invalid nonce.', 'wc-bundle')
            ]
        ]);
    }

    $products = array_map('intval', $_POST['products']);

    // get id from product by title "Bundle"
    $placeholder_id = get_option('my_bundle_base_product_id');

    $cart_item_data = [
        'is_bundle' => true,
        'bundle_products' => $products,
        'custom_price' => array_sum(
            array_map(
                function ($product_id) {
                    $product = wc_get_product($product_id);
                    return $product ? $product->get_price() : 0;
                }, $products
            )
        ),
    ];

    $added = WC()->cart->add_to_cart(
        $placeholder_id,
        1,
        0,
        [],
        $cart_item_data
    );

    if($added){
        wp_send_json_success([
            'cart_url' => wc_get_cart_url(),
            'cart_item_data' => $cart_item_data,
            'bundle_products' => $products,
            'placeholder_id' => $placeholder_id
        ]);
    }else{
        wp_send_json_error([
            'error' => [
                'message' => __('There was an error adding the bundle to the cart.', 'wc-bundle'),
                'placeholder_id' => $placeholder_id
            ]
        ]);
    }

    wp_die();
}


add_action('wp_ajax_add_to_preview', 'add_to_preview');
add_action('wp_ajax_nopriv_add_to_preview', 'add_to_preview');
function add_to_preview(){

    $product_ID = $_POST['product_id'] ?? null;

    $product = wc_get_product($product_ID);
    $img = $product->get_image();

    wp_send_json_success([
        'product_id' => $product,
        'product_img' => $img
    ]) ;

    wp_die();

}


add_action('woocommerce_get_item_data', function ($item_data, $cart_item) {

    if (!empty($cart_item['bundle_products'])) {

        $item_data[] = [
            'name' => 'Bundle Products',
            'value' => implode(', ', array_map(function ($product_id) {
                $product = wc_get_product($product_id);
                return $product ? $product->get_title() : '';
            }, $cart_item['bundle_products']))
        ];

    }
    return $item_data;

    
}, 10, 2);


add_action('woocommerce_before_calculate_totals', function ($cart) {
    foreach($cart->get_cart() as $cart_item){
        if(!empty($cart_item['custom_price'])){
            $cart_item['data']->set_price($cart_item['custom_price']);
            
        }
    }
});


?>