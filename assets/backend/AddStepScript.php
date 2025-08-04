<?php

// Register endpoint AJAX
add_action('admin_enqueue_scripts', function () {
    $base_url = admin_url('admin-ajax.php');

    $args = [
        'ajax_url' => $base_url,
    ];

    wp_register_script('AddStep_Script', plugin_dir_url(__FILE__) . 'AddStepScript.js', ['jquery'], '1.0.0', true);
    wp_localize_script('AddStep_Script', 'AddStep_URL', $args);
    wp_enqueue_script('AddStep_Script');
});

add_action('wp_ajax_add_step_script', 'add_step_script');
add_action('wp_ajax_nopriv_add_step_script', 'add_step_script');

function add_step_script() {

    $steps = get_post_meta($post->ID, '_wc_bundle_steps', true) ?: [];
    wp_nonce_field('wc_bundle_steps_nonce', 'wc_bundle_steps_nonce');

    require dirname(__DIR__, 1) . '/template-parts/step.php';

    die();
}



?>