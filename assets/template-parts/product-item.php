<li class="product-item" data-product-length="<?= $length ?>" data-product-width="<?= $largura ?>" data-product-height="<?= $altura ?>">
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
    <button class="addtobundle button" data-product-id="<?= $product->get_id() ?>" ><?= __('Add to Bundle', 'wc-bundle'); ?></button>
</li>