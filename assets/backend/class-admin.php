<?php

class My_Bundle_Plugin {
    
    public function __construct() {
        
        // 1. CPT for Bundles
        add_action('init', [$this, 'my_wc_bundle_cpt']);

        // 2. Metabox for bundle steps
        add_action('add_meta_boxes', [$this, 'my_wc_bundle_metabox']);

        // 3. Save metabox
        add_action('save_post_wc_bundle', [$this, 'save_bundle_steps']);

        // 4. Add Shortcode Column
        add_filter('manage_wc_bundle_posts_columns', [$this, 'add_shortcode_column_to_bundles']);

        // 5. Add Shortcode
        add_action('manage_wc_bundle_posts_custom_column', [$this, 'fill_shortcode_column_in_bundles'], 10, 2);

        // 6. Deactivate my plugin if WooCommerce is deactivated
        add_action('deactivated_plugin', [$this, 'meu_plugin_woocommerce_desativado']);

        // 7. Tutorial
        add_action('admin_menu', [$this, 'add_tutorial_submenu'], 0);


    }

    // 1. CPT for Bundles
    public function my_wc_bundle_cpt() {
        
        register_post_type(
            'wc_bundle', 
            [
                'label' => __('Bundles', 'my-bundle-plugin'),
                'public' => false,
                'show_ui' => true,
                'supports' => ['title'],
                'menu_icon' => 'dashicons-cart',
            ]
        );

    }




    // 2. Metabox for bundle steps, Create elements, Save metabox
    public function my_wc_bundle_metabox() {
        add_meta_box(
            'wc_bundle_steps',
            'Bundle steps',
            [$this, 'render_bundle_steps_metabox'],
            'wc_bundle',
            'normal'
        );
    }
    function render_bundle_steps_metabox($post) {

        $steps = get_post_meta($post->ID, '_wc_bundle_steps', true) ?: [];
        wp_nonce_field('wc_bundle_steps_nonce', 'wc_bundle_steps_nonce');

        require dirname(__DIR__, 1) . '/template-parts/bundle-steps.php';
        do_action('Load_bundle_steps' , $steps);

        ?>
        <?php
    }
    function save_bundle_steps($post_id) {
        if (isset($_POST['steps'])) {
            update_post_meta($post_id, '_wc_bundle_steps', $_POST['steps']);
        }
    }




    // Add Shortcode Column to Each Bundle && Add Shortcode inside the column
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
    function fill_shortcode_column_in_bundles($column, $post_id) {
        if ($column === 'shortcode') {
            echo '[my_wc_bundle id="' . $post_id . '"]';
        }
    }





    // Deactivate my plugin if WooCommerce is deactivated
    function meu_plugin_woocommerce_desativado($plugin) {
        if ($plugin === 'woocommerce/woocommerce.php') {
            deactivate_plugins(plugin_basename(__FILE__));
            add_action('admin_notices', function () {
                echo '<div class="notice notice-error"><p>WooCommerce foi desativado, então o plugin "Meu Plugin" também foi desativado automaticamente.</p></div>';
            });
        }
    }




    // Add submenu Tutorial && Render Tutorial Page
    public function add_tutorial_submenu() {
        add_submenu_page(
            'edit.php?post_type=wc_bundle',
            'Tutorial',
            'Tutorial',
            'manage_options',
            'bundles_tutorial',
            [$this, 'render_tutorial_page'],
            1
        );
    }
    function render_tutorial_page() {

        require_once dirname(__DIR__, 1) . '/TutorialPage.php';

        do_action('my_bundle_plugin_tutorial');

    }



}
new My_Bundle_Plugin();





?>