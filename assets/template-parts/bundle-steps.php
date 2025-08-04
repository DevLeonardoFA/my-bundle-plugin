<?php

function wc_bundle_steps( $steps ) {

    ?>

    <div id="bundle-steps">
        <?php 
        
        foreach ($steps as $i => $step) : 

            require dirname(__DIR__, 1) . '/template-parts/step.php';

        endforeach; 
        
        ?>
    </div>
    <button type="button" id="add-step" class="button">+ Add New Stage</button>

    <?php

}

add_action('Load_bundle_steps', 'wc_bundle_steps');