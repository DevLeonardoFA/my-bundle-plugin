<?php 

/**
 * Plugin Name: My Bundle Plugin
 * Version: 1.0.0
 * Author: Leonardo F. Alonso
 * 
*/

defined ( 'ABSPATH' ) || exit;

// ao ativar o plugin
require_once __DIR__ . '/assets/controller/CreateDeleteProBase.php';



// Carregar Classe Admin
require_once __DIR__ . '/assets/backend/class-admin.php';
require_once __DIR__ . '/assets/backend/AddStepScript.php';



// Add Shortcode Column
function add_shortcode_column_to_bundles($columns) {
    $new_columns = [];

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        if ($key === 'title') {
            $new_columns['shortcode'] = 'Shortcode';
        }
    }

    return $new_columns;
}
add_filter('manage_wc_bundle_posts_columns', 'add_shortcode_column_to_bundles');

// Add Shortcode inside the column
function fill_shortcode_column_in_bundles($column, $post_id) {
    if ($column === 'shortcode') {
        echo '[my_wc_bundle id="' . $post_id . '"]';
    }
}
add_action('manage_wc_bundle_posts_custom_column', 'fill_shortcode_column_in_bundles', 10, 2);



// Carregar Shortcodes
require_once __DIR__ . '/assets/frontend/shortcodes.php';


// Add Scripts Backend
add_action('admin_enqueue_scripts', function(){
    wp_enqueue_style('FrontEndStyle', plugin_dir_url(__FILE__) . './assets/frontend/FrontEndStyle.css');
    wp_enqueue_style('BackEndStyle', plugin_dir_url(__FILE__) . './assets/backend/BackEndStyle.css');
});

// Add Styles
add_action('wp_enqueue_scripts', function(){
    wp_enqueue_style('FrontEndStyle', plugin_dir_url(__FILE__) . './assets/frontend/FrontEndStyle.css');
});


// registrar o tipo de produto
add_filter('product_type_selector', function($types) {
    $types['bundle'] = 'Bundle Personalizado';
    return $types;  
});


// Carregar Classe do produto
add_action('woocommerce_loaded', function() {
    require_once __DIR__ . '/includes/class-wc-product-bundle.php';
});


require_once __DIR__ . '/assets/frontend/BundleAjax.php';
require_once __DIR__ . '/assets/controller/AjaxSendToCart.php';



wp_localize_script('wc-bundle-frontend-js', 'myBundleVars', [
    'ajax_url' => admin_url('admin-ajax.php'),
]);


add_action('woocommerce_loaded', function() {

    require_once __DIR__ . '/includes/class-wc-product-bundle.php';

    add_filter('product_type_selector', function($types) {
        $types['bundle'] = 'Bundle Personalizado';
        return $types;
    });

});


add_action('init', function() {
    
    if(isset($_GET['clear_cart'])) {
        WC()->cart->empty_cart();
        wp_redirect(wc_get_cart_url());
        exit;
    }

})








?>