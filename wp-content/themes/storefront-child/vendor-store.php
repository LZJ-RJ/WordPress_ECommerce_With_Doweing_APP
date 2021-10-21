<?php
$term_slug = $parent->slug . '-discount';
$vendor_store_term = get_term_by('slug', $term_slug, 'product_cat');
$vendor_store_term_id = $vendor_store_term->term_id;
$vendor_store_products = wc_get_products(array(
    'limit' => -1,
    'post_status' => "publish",
    'category' => array($term_slug),
));
if (count($vendor_store_products) > 7) {
    $is_more = true;
    $more_link = get_term_link($vendor_store_term_id, 'product_cat');
} else {
    $is_more = false;
}
?>
<!--********************優惠商品********************-->
    <div class="vendor-page-store-product">
    <div class="header">
        <h3>優惠商品</h3>
        <span>
                <?= ($is_more) ? '<a href="' . $more_link . '">更多</a>' : '' ?>
            </span>
    </div>
    <div class="vendor-page-store-product-pd">
        <?php
        foreach ($vendor_store_products

        as $product) {
        $thumbnail_id = $product->get_image_id();
        $imageUrl = wp_get_attachment_url($thumbnail_id);
        $link = get_permalink($product->get_id());
        ?>
        <div class="store-product-single-pd-cat-info">
            <a href="<?= $link ?>">
                <img
                        class="store-product-single-pd-cat-thumbnail"
                        referrerpolicy="no-referrer"
                        src="<?= $imageUrl ?>"
                />
                <span><?= $product->get_name() ?></span>
            </a>
            <?php
            if ($price_html = $product->get_price_html()) {
                echo '<span class="price">' . $price_html . '</span>';
            }

            add_to_cart($product);

        echo '</div>';
        }
            ?>
        </div>
    </div>
<?php
$term_slug = $parent->slug . '-recommend';
$vendor_store_term = get_term_by('slug', $term_slug, 'product_cat');
$vendor_store_term_id = $vendor_store_term->term_id;
$vendor_store_products = wc_get_products(array(
    'limit' => -1,
    'post_status' => "publish",
    'category' => array($term_slug),
));
if (count($vendor_store_products) > 7) {
    $is_more = true;
    $more_link = get_term_link($vendor_store_term_id, 'product_cat');
} else {
    $is_more = false;
}
?>
<!--********************推薦商品********************-->
    <div class="vendor-page-store-product">
    <div class="header">
        <h3>推薦商品</h3>
        <span>
                <?= ($is_more) ? '<a href="' . $more_link . '">更多</a>' : '' ?>
            </span>
    </div>
    <div class="vendor-page-store-product-pd">
        <?php
        foreach ($vendor_store_products as $product) {
        $thumbnail_id = $product->get_image_id();
        $imageUrl = wp_get_attachment_url($thumbnail_id);
        $link = get_permalink($product->get_id());
        ?>
        <div class="store-product-single-pd-cat-info">
            <a href="<?= $link ?>">
                <img
                        class="store-product-single-pd-cat-thumbnail"
                        referrerpolicy="no-referrer"
                        src="<?= $imageUrl ?>"
                />
                <span><?= $product->get_name() ?></span>
            </a>
            <?php
            if ($price_html = $product->get_price_html()) {
                echo '<span class="price">' . $price_html . '</span>';
            }

            add_to_cart($product);

        echo '</div>';
        }
            ?>
        </div>
    </div>
<?php

function add_to_cart($product)
{
    $args = array();
    if ($product) {
        $defaults = array(
            'quantity' => 1,
            'class' => implode(
                ' ',
                array_filter(
                    array(
                        'button',
                        'product_type_' . $product->get_type(),
                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                        $product->supports('ajax_add_to_cart') && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
                    )
                )
            ),
            'attributes' => array(
                'data-product_id' => $product->get_id(),
                'data-product_sku' => $product->get_sku(),
                'aria-label' => $product->add_to_cart_description(),
                'rel' => 'nofollow',
            ),
        );

        $args = apply_filters('woocommerce_loop_add_to_cart_args', wp_parse_args($args, $defaults), $product);

        if (isset($args['attributes']['aria-label'])) {
            $args['attributes']['aria-label'] = wp_strip_all_tags($args['attributes']['aria-label']);
        }

        echo apply_filters(
            'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
            sprintf(
                '<a href="%s" data-quantity="%s" class="%s" %s></a>',
                esc_url($product->add_to_cart_url()),
                esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
                esc_attr(isset($args['class']) ? $args['class'] : 'button'),
                isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
            ),
            $product,
            $args
        );
    }
}
