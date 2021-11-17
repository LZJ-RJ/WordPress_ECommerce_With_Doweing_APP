<?php
/**
 * Wishlist
 */
//include_once("wp-config.php");
//include_once("wp-includes/wp-db.php");

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_my_wishlist');

global $wpdb;
$sql = "SELECT prod_id FROM ".$wpdb->prefix."yith_wcwl WHERE user_id=".wp_get_current_user()->ID;
$results = $wpdb->get_results($sql);
?>
<!--*********************願望清單*******************-->
<div class="vendor-page-wishlist-product">
    <div class="vendor-page-wishlist-product-pd">
        <?php
        foreach ($results as $item) {
        $product = wc_get_product($item->prod_id);
        $thumbnail_id = $product->get_image_id();
        $imageUrl = wp_get_attachment_url($thumbnail_id);
        $link = get_permalink($product->get_id());
        ?>
        <div class="wishlist-product-single-pd-cat-info">
            <a href="<?= $link ?>">
                <img
                    class="wishlist-product-single-pd-cat-thumbnail"
                    referrerpolicy="no-referrer"
                    src="<?= $imageUrl ?>"
                />
                <span><?= $product->get_name() ?><a href="'.esc_url( add_query_arg( 'remove_from_wishlist', $product->get_id() ) ).'" class="remove remove_from_wishlist" title="'.esc_html( apply_filters( 'yith_wcwl_remove_product_wishlist_message_title', __( 'Remove this product', 'yith-woocommerce-wishlist' ) ) ).'">&times;</a></span>
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
do_action( 'woocommerce_after_account_my_wishlist'); ?>
