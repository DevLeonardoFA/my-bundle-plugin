<?php

add_shortcode('my_wc_bundle', function($atts) {

    if (!isset($atts['id'])) return 
        __('Missing "id" attribute', 'wc-bundle');

    $bundle_id = $atts['id'];
    $steps = get_post_meta($bundle_id, '_wc_bundle_steps', true);
    
    if (empty($steps)) return 
        __('This bundle has no steps', 'wc-bundle');

    ob_start();


    ?>
    <div class="wc-bundle-wizard loading" data-bundle-id="<?= esc_attr($bundle_id) ?>">

        <div class="progressbar" data-parts="<?= count($steps) ?>">
            <div class="line"></div>
            <div class="dots">
            <?php foreach($steps as $i => $step) : ?>
                <div class="dot"></div>
            <?php endforeach; ?>
            </div>
        </div>

        <div class="wc-bundle-steps">
            <?php 
            $first = true;
            $s_a = 0;

            foreach ($steps as $i => $step) : ?>
                <div class="step" data-title="<?= esc_attr($step['title']) ?>" data-step-index="<?= $i ?>" <?= $first ? '' : 'style="display:none;"' ?>>
                    
                    <h2><?= esc_html($step['title']) ?></h2>

                    <div class="products-container" data-step-slug="<?= esc_attr($step['query_slug'] ?? '') ?>">
                        
                        <ul class="products">
                            <?php if ($first) : 
                                
                                $rules = $step['rules'] ?? 'NA';

                                $category_id = $step['category'];
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





                                $products = new WP_Query($args);
                                if ($products->have_posts()) :
                                    while ($products->have_posts()) : $products->the_post();

                                        // get woocommerce shiping dimensions
                                        $product = wc_get_product(get_the_ID());
                                        $length = $product->get_length() ?? 0;
                                        $largura = $product->get_width() ?? 0;
                                        $altura = $product->get_height() ?? 0;


                                        ?>

                                        <li class="product-item" rules="<?= $rules ?>" data-product-length="<?= $length ?>" data-product-width="<?= $largura ?>" data-product-height="<?= $altura ?>">
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
                                            <button class="addtobundle button" data-product-id="<?= get_the_ID() ?>"><?= __('Add to Bundle', 'wc-bundle') ?></button>
                                        </li>

                                        <?php

                                    endwhile;
                                endif;
                                
                                
                                ?>


                            <?php endif; ?>
                        </ul>

                    </div>

                    <div class="step-actions">
                        <?php if ($s_a > 0) : ?>
                            <button type="button" class="prev-step button" data-index="<?= $s_a ?>"><?= __('Back Step', 'wc-bundle'); ?></button>
                        <?php endif; ?>
                            <button type="button" class="next-step button" data-index="<?= $s_a ?>"><?= __('Next Step', 'wc-bundle'); ?></button>
                    </div>

                </div>
                <?php $first = false; $s_a++; ?>
            <?php endforeach; ?>
            
            <div class="final-step step" data-step-index="final" <?= $first ? '' : 'style="display:none;"' ?>>
                <h2><?= __('Finalize your package by adding it to your cart', 'wc-bundle'); ?></h2>
                <div class="step-actions">
                    <?php if ($i > 0) : ?>
                        <button type="button" class="prev-step button"><?= __('Back Step', 'wc-bundle'); ?></button>
                    <?php endif; ?>
                    <button type="button" class="next-step button final-step-btn" data-is-last="true">
                        <?= __('Add to Cart', 'wc-bundle'); ?>
                    </button>
                </div>
            </div>
            
            

            <div class="bundle-preview">
                <h2><?= __('Your Bundle', 'wc-bundle'); ?></h2>
                <ul class="products">
                </ul>
            </div>


            <div class="popup_msg">
                <p></p>
                <button type="button" class="button close-popup"><?= __('Close', 'wc-bundle'); ?></button>
            </div>




        </div>
    </div>
    <?php
    return ob_get_clean();
});


?>