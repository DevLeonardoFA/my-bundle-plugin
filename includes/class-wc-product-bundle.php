<?php

if(!defined('ABSPATH')) exit;

if(!class_exists('WC_Product')) return;

class WC_Product_Bundle extends WC_Product_Simple {

    public function __construct($product) {
        parent::__construct($product);
        $this->product_type = 'bundle';
    }

    public function get_price($context = 'view') {

        $price = 0;

        if($this->get_meta('selected_products')) {
            foreach($this->get_meta('selected_products') as $product_id) {

                $product = wc_get_product($product_id);

                if($product){
                    $price += $product->get_price();
                }

            }
        }

        return ($context === 'view') ? wc_get_price_to_display($this, ['price' => $price]) : $price;

    }

}


?>