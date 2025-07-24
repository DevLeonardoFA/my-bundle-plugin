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

    $rules = [
        'NA' => 'No Rule',
        'SizeRule' => 'Size equal or bigger'
    ];

    $i = count($steps);

    ?>

        <div class="step" data-index="<?= $i ?>">
            <label>Stage Title:</label>
            <input type="text" name="steps[<?= $i ?>][title]" value="<?= esc_attr($step['title']) ?>" placeholder="Ex: Escolha o vaso" required>

            <label>Category:</label>
            <select name="steps[<?= $i ?>][category]">
                <?php foreach (get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]) as $cat) : ?>
                    <option value="<?= $cat->term_id ?>" <?= selected($cat->term_id, $step['category'] ?? '') ?>>
                        <?= $cat->name ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Rules</label>
            <select name="steps[<?= $i ?>][rules]" value="<?= $step['rules'] ?? '' ?>">
                <?php foreach ($rules as $value => $label) : ?>
                    <option value="<?= $value ?>" <?= selected($value, $step['rules'] ?? '') ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            
            <button type="button" class="remove-step button">Delete</button>
        </div>
        

    <?php

    die();
}



?>