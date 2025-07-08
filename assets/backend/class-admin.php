<?php

class My_Bundle_Plugin {
    
    public function __construct() {
        
        // 1. CPT for Bundles
        add_action('init', [$this, 'my_wc_bundle_cpt']);
        // add_action('init', [$this, 'create_placeholder_product']);

        // 2. Metabox for bundle steps
        add_action('add_meta_boxes', [$this, 'my_wc_bundle_metabox']);

        // 3. Save metabox
        add_action('save_post_wc_bundle', function($post_id) {
            if (isset($_POST['steps'])) {
                update_post_meta($post_id, '_wc_bundle_steps', $_POST['steps']);
            }
        });

    }

    public function my_wc_bundle_cpt() {
        
        register_post_type('wc_bundle', [
            'label' => 'Bundles',
            'public' => false,
            'show_ui' => true,
            'supports' => ['title'],
            'menu_icon' => 'dashicons-cart',
        ]);

    }

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

        $rules = [
            'NA' => 'No Rule',
            'SizeRule' => 'Size equal or bigger'
        ];

        ?>
        <div id="bundle-steps">
            <?php foreach ($steps as $i => $step) : ?>
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
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-step" class="button">+ Add New Stage</button>
        <?php
    }

}
new My_Bundle_Plugin();





?>