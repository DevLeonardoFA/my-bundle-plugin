<?php 

/**
 * Plugin Name: My Bundle Plugin
 * Plugin URI: https://github.com/DevLeonardoFA/my-bundle-plugin
 * Version: 1.0.0
 * Author: Leonardo F. Alonso
 * Author URI: https://leonardofalonso.vercel.app/
 * Description: An amazing plugin for create your own custom bundle with WooCommerce.
 * documentation: https://github.com/DevLeonardoFA/my-bundle-plugin
*/

defined ( 'ABSPATH' ) || exit;

// Simple verification if WooCommerce is active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

    // all Required Files
    require_once __DIR__ . '/assets/controller/CreateDeleteProBase.php'; // Create & Delete Product Placeholder
    require_once __DIR__ . '/assets/template-parts/product-item.php'; // Product Item

    // Backend
    require_once __DIR__ . '/assets/backend/class-admin.php'; // Admin Class
    require_once __DIR__ . '/assets/backend/AddStepScript.php'; // Add function to add new steps on backend cpt
    require_once __DIR__ . '/assets/controller/AjaxSendToCart.php'; // This one is for take all products ID, create a bundle and send to cart

    // Frontend
    require_once __DIR__ . '/assets/frontend/shortcodes.php'; // Shortcode
    require_once __DIR__ . '/assets/frontend/BundleAjax.php'; // Ajax load products


} else {

    add_action('admin_notices', function() {
        echo '<div class="error"><p>My Bundle Plugin requires WooCommerce to be active.</p></div>';
    });

    deactivate_plugins(__FILE__);

    return;
}


// Add Scripts Backend
add_action('admin_enqueue_scripts', function(){
    wp_enqueue_style('FrontEndStyle', plugin_dir_url(__FILE__) . './assets/frontend/FrontEndStyle.css');
    wp_enqueue_style('BackEndStyle', plugin_dir_url(__FILE__) . './assets/backend/BackEndStyle.css');
});


// Add Styles
add_action('wp_enqueue_scripts', function(){
    wp_enqueue_style('FrontEndStyle', plugin_dir_url(__FILE__) . './assets/frontend/FrontEndStyle.css');
});



?>