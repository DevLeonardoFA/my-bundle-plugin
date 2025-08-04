<?php

class wc_bundle_product_item_html {

    public function __construct($product_id) {

        $this->item_html($product_id);

    }

    public function item_html($id) {

        $product = wc_get_product($id);
        $length = $product->get_length() ?? 0;
        $largura = $product->get_width() ?? 0;
        $altura = $product->get_height() ?? 0;

        
        if( $product->is_type('variation') ) {
            $variation = new WC_Product_Variation( $id );
            $image_id = wp_get_attachment_image_url( $variation->get_image_id() );
        }

        ?>

        <li class="product-item" data-product-length="<?= $length ?>" data-product-width="<?= $largura ?>" data-product-height="<?= $altura ?>" >
            <img 
                src="<?= $product->is_type('variation') ? $image_id : get_the_post_thumbnail_url() ?>" 
                alt="<?= get_the_title() ?>" 
                width="150">
            <div class="content">
                <h4>
                    <a href="<?= get_the_permalink() ?>">
                        <?= get_the_title() ?>
                    </a>
                </h4>

                <?php if( $product->is_type('variation') ) { ?>
                    <span class="attributes">
                        <?php 

                            $attributes = $product->get_attributes();

                            if (!empty($attributes)) {
                                foreach ($attributes as $attribute => $value) {
                                    if(empty($value)) continue;

                                    // get atribute name by slug
                                    $attribute = wc_attribute_label($attribute);

                                    echo $attribute . ': ' . $value . '<br>';
                                }
                            }
                        
                        ?>
                    </span>
                <?php } ?>
                
                <span class="price">
                    <?php

                        if( $product->is_on_sale() ) {
                            echo wc_price($product->get_sale_price());
                        }
                        elseif( $product->is_type('variation') ) {
                            echo wc_price($product->get_regular_price());
                        }
                        else {
                            echo wc_price($product->get_price());
                        }

                    ?>
                </span>

                <button class="addtobundle button" data-product-id="<?= $product->get_id() ?>" ><?= __('Add to Bundle', 'wc-bundle'); ?></button>
            </div>
            
        </li>
        

        <?php
    }

}

add_action('wc_bundle_product_item', 'wc_bundle_product_item_html');