<?php 

// executar quando o plugin for ativado

function activate_my_bundle_plugin() {
    flush_rewrite_rules();
    create_placeholder_product();
}
add_action('activated_plugin', 'activate_my_bundle_plugin');


// executar quando o plugin for desativado
function deactivate_my_bundle_plugin() {
    flush_rewrite_rules();
    delete_placeholder_product();
}
add_action('deactivated_plugin', 'deactivate_my_bundle_plugin');


function create_placeholder_product() {
    
    $existing = get_option('my_bundle_base_product_id');
    if ($existing && get_post_status($existing) === 'publish') {
        return $existing;
    }

    $product = new WC_Product_Simple();
    $product->set_name('Bundle Base');
    $product->set_status('private');
    $product->set_catalog_visibility('hidden');
    $product->set_regular_price(0);
    $product->set_price(0);
    $product->save();

    update_option('my_bundle_base_product_id', $product->get_id());

    return $product->get_id();

}

function delete_placeholder_product() {
    $product_id = get_option('my_bundle_base_product_id');
    if ($product_id) {
        wp_delete_post($product_id, true);
        delete_option('my_bundle_base_product_id');
    }
}




?>