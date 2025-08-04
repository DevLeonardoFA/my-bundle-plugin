<?php

    $rules = [
        'NA' => 'No Rule',
        'SizeRule' => 'Size equal or bigger'
    ];

?>
<div class="step" data-index="<?= $i ?>" >
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

    <label for="steps[<?= $i ?>][optional]">This step is optional?</label>
    <select name="steps[<?= $i ?>][optional]" id="steps[<?= $i ?>][optional]" value="<?= $step['optional'] ?? '' ?>">
        <option value="0" <?= selected('0', $step['optional'] ?? '') ?>>No</option>
        <option value="1" <?= selected('1', $step['optional'] ?? '') ?>>Yes</option>
    </select>
    
    <button type="button" class="remove-step button">Delete</button>
</div>