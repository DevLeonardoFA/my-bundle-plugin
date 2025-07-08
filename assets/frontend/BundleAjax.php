<?php

// Registrar endpoint AJAX
add_action('wp_enqueue_scripts', function () {
    $base_url = admin_url('admin-ajax.php');

    $args = [
        'ajax_url' => $base_url,
    ];

    wp_register_script('wc_bundle', plugin_dir_url(__FILE__) . 'BundleAjax.js', ['jquery'], '1.0.0', true);
    wp_localize_script('wc_bundle', 'wc_bundle_ajax', $args);
    wp_enqueue_script('wc_bundle');
});

add_action('wp_ajax_wc_bundle_load_products', 'wc_bundle_load_products');
add_action('wp_ajax_nopriv_wc_bundle_load_products', 'wc_bundle_load_products');

function wc_bundle_load_products() {

    $step_slug = sanitize_text_field($_POST['step_slug']);
    $bundle_id = intval($_POST['bundle_id']);
    $step_index = intval($_POST['step_index']);

    $steps = get_post_meta($bundle_id, '_wc_bundle_steps', true);
    $category_id = $steps[$step_index]['category'];
    $rules = $steps[$step_index]['rules'];
    
    
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

        // WOOCOMMERCE SHIPING DIMENSIONS

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

        echo '<ul class="products-list">';
        while ($products->have_posts()) {
            $products->the_post();
            global $product;

            // get woocommerce shiping dimensions
            // $product = wc_get_product(get_the_ID());
            $length = $product->get_length() ?? 0;
            $largura = $product->get_width() ?? 0;
            $altura = $product->get_height() ?? 0;


            ?>

                <li class="product-item" data-product-length="<?= $length ?>" data-product-width="<?= $largura ?>" data-product-height="<?= $altura ?>">
                    <a href="<?= get_the_permalink() ?>">
                        <img 
                        src="<?= get_the_post_thumbnail_url() ?>" 
                        alt="<?= get_the_title() ?>" 
                        width="150">
                    </a>
                    <h4>
                        <a href="<?= get_the_permalink() ?>">
                            <?= get_the_title() ?>
                        </a>
                    </h4>
                    <button class="addtobundle button" data-product-id="<?= $product->get_id() ?>" ><?= __('Add to Bundle', 'wc-bundle'); ?></button>
                </li>
                        
            <?php


        }
        echo '</ul>';
        wp_reset_postdata();
    } else {
        echo '<p>'. __('No products found', 'wc-bundle') .'</p>';
    }
    wp_die();
}











?>