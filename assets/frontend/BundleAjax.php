<?php

// Registrar endpoint AJAX
add_action('wp_enqueue_scripts', function () {
    $base_url = admin_url('admin-ajax.php');

    $args = [
        'ajax_url' => $base_url,
    ];

    wp_register_script('BundleAjax_Script', plugin_dir_url(__FILE__) . 'BundleAjax.js', ['jquery'], '1.0.0', true);
    wp_localize_script('BundleAjax_Script', 'Bundle_URL', $args);
    wp_enqueue_script('BundleAjax_Script');
});

add_action('wp_ajax_LoadProducts_FrontEnd', 'LoadProducts_FrontEnd');
add_action('wp_ajax_nopriv_LoadProducts_FrontEnd', 'LoadProducts_FrontEnd');


function LoadProducts_FrontEnd() {

    $step_slug = sanitize_text_field($_POST['step_slug']);
    $bundle_id = intval($_POST['bundle_id']);
    $step_index = intval($_POST['step_index']);

    $steps = get_post_meta($bundle_id, '_wc_bundle_steps', true);
    $category_id = $steps[$step_index]['category'];
    $rules = $steps[$step_index]['rules'];
    $optional = $steps[$step_index]['optional'];  
    
    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => [
            [
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_id,
            ]
        ]
    ];


    if($rules != 'NA'){

        $lwh = $_POST['lwh'];

        $args['meta_query'] = [
            [
                'key' => '_length',
                'value' => $lwh[0],
                'compare' => '>=',
                'type'    => 'NUMERIC'
            ],
            [
                'key' => '_width',
                'value' => $lwh[1],
                'compare' => '>=',
                'type'    => 'NUMERIC'
            ],
            [
                'key' => '_height',
                'value' => $lwh[2],
                'compare' => '>=',
                'type'    => 'NUMERIC'
            ],
        ];

    }


    $products = new WP_Query($args);
    if ($products->have_posts()) {

        echo '<ul class="products-list" ' . ($optional == 1 ? 'data-optional="true"' : '') . '>';
        while ($products->have_posts()) {

            $products->the_post();
            global $product;

            if( $product->is_type('simple') ){

                $item = new wc_bundle_product_item_html($product->get_id());
                            
            }elseif( $product->is_type('variable') ){
                
                $variations = $product->get_children();
                foreach ($variations as $variation) { 

                    $item = new wc_bundle_product_item_html($variation);
                    
                }


            }

        }
        echo '</ul>';
        wp_reset_postdata();
    } else {
        echo '<p>'. __('No products found', 'wc-bundle') .'</p>';
    }

    wp_die();
}











?>