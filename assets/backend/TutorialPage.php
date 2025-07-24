<?php 

function my_bundle_plugin_tutorial_html() {

    $steps = [
        ['title' => 'Step 1', 'desc' => 'Add products to your store', 'icon' => '🛒'],
        ['title' => 'Step 2', 'desc' => 'Create some categories', 'icon' => '📂'],
        ['title' => 'Step 3', 'desc' => 'Add products to respective categories', 'icon' => '🔗'],
        ['title' => 'Step 4', 'desc' => 'Create a bundle', 'icon' => '📦'],
        ['title' => 'Step 5', 'desc' => 'On each step of the bundle, define a title and select the category', 'icon' => '✏️'],
        ['title' => 'Step 6 (optional)', 'desc' => 'Select the rule if necessary', 'icon' => '⚙️'],
        ['title' => 'Final', 'desc' => 'Done! Now you can add the bundle to your page using the shortcode.', 'icon' => '✅'],
    ];


    ?>
    <div class="tutorial">
        <h1>Tutorial</h1>

        <div class="tutorial-steps">

            <?php foreach ($steps as $step) { ?>
                <div class="step-box">
                    <div class="step-title"><?= $step['icon'] ?> <strong><?= $step['title'] ?></strong></div>
                    <div class="step-desc"><?= $step['desc'] ?></div>
                </div>
            <?php } ?>
        
        </div>
    </div>
    <?php

}

add_action('my_bundle_plugin_tutorial', 'my_bundle_plugin_tutorial_html');