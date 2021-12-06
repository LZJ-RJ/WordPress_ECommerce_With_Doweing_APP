<?php
/**
 * The template for displaying search results pages.
 *
 * @package storefront
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );

$current_category = get_queried_object();
$parent = $current_category;

if ($current_category->parent == 0) {
    $is_parent = true;
} else {
    $is_parent = false;
    while ($parent->parent != 0) {
        $parent = get_term($parent->parent);
    }
}
if ( explode('-', $parent->slug)[0] == 'vendor') {
    $is_vendor = true;
} else {
    $is_vendor = false;
}

echo '<script src="' . get_stylesheet_directory_uri() . '/assets/js/vendor.js"></script>';
echo '<link rel="stylesheet" href="/wp-content/themes/storefront-child/assets/css/vendor.css" media="all">';

include_once ABSPATH . 'wp-content/themes/storefront-child/original-archive-product.php';

get_footer();
