<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
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

if ($is_vendor) {
    echo '<script src="' . get_stylesheet_directory_uri() . '/assets/js/vendor.js"></script>';
    echo '<link rel="stylesheet" href="/wp-content/themes/storefront-child/assets/css/vendor.css" media="all">';
}

// Redirect
$divide_by_slash = explode('/', $_SERVER['REQUEST_URI']);
$is_search = false;
if ($is_parent &&
    $parent->slug == 'doweing-hot-product' ||
    $parent->slug == 'doweing-recommend' ||
    $parent->slug == 'doweing-special-plan'
) {
//    not redirect
} else if ($parent->slug != '' && $current_category->slug != '' && $divide_by_slash[count($divide_by_slash)-2] == $current_category->slug && $current_category->slug == $parent->slug) {
    header("Location: ".get_term_link($current_category->term_id, 'product_cat').'/'.$current_category->slug.'-store');
} else if ( isset($_GET['s']) ){
    $is_search = true;
}
if (!$is_search):
    $thumbnail_id = get_term_meta($parent->term_id, 'thumbnail_id', true);
    $imageUrl = wp_get_attachment_url($thumbnail_id);

    $bg_img_id = get_term_meta($parent->term_id, 'vendor_category_bg_img', 1);
    $app_link = get_term_meta($parent->term_id, 'vendor_category_app_link', 1);
    $bgImgUrl = wp_get_attachment_url($bg_img_id);
    if (!($is_parent &&
        $parent->slug == 'doweing-hot-product' ||
        $parent->slug == 'doweing-recommend' ||
        $parent->slug == 'doweing-special-plan')
    ) {
    ?>
        <header class="woocommerce-products-header" style="background-size: 100% 100%!important;background: linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.6)), no-repeat url(<?=$bgImgUrl?>);">
            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
            <div class="vendor-category-img">
                <img
                        class="vendor-pd-cat-thumbnail"
                        referrerpolicy="no-referrer"
                        src="<?=$imageUrl?>"
                />
            </div>
            <div class="vendor-category-description">
                <span class="woocommerce-products-header__title page-title"><?php
                    if ($is_vendor && !$is_parent) {
                        echo $parent->name;
                    } else {
                        woocommerce_page_title();
                    }
                    ?></span>

            <?php endif; ?>

            <?php
            /**
             * Hook: woocommerce_archive_description.
             *
             * @hooked woocommerce_taxonomy_archive_description - 10
             * @hooked woocommerce_product_archive_description - 10
             */

            if ( $parent && ! empty( $parent->description ) ) {
                echo '<div class="term-description">' . wc_format_content( wp_kses_post( $parent->description ) ) . '</div>';
            }
            ?>
            </div>
            <div class="vendor-category-app-link">
                <a href="<?=$app_link?>">前往頻道</a>
            </div>
        </header>
    <?php
    }
    if ($is_vendor){
        $vendor_child = get_terms(
                array(
                        'taxonomy' => 'product_cat',
                        'parent' => $parent->term_id,
                        'hide_empty' => false,
                    ));
        echo '<div class="child-term-tab">';
        foreach ($vendor_child as $child_item) {
            $child_slug = $child_item->slug;
            $child_term = get_term_by('slug', $child_slug, 'product_cat');
            $href = get_term_link($child_term->term_id, 'product_cat');
            echo '<a class="child-term-href ';
            if ($child_term->term_id == $current_category->term_id ||
                strpos($current_category->slug, $child_term->slug) !== false ||
                ($current_category->parent == get_term_by('slug', $child_slug, 'product_cat')->term_id) ) {
                echo 'child-term-href-selected"';
            } else {
                echo '"';
            }
            echo 'href="' . $href . '">'.$child_term->name.'</a>';
        }
        echo '</div>';

        if ($current_category->slug != $parent->slug . '-store' && strpos($current_category->slug, $parent->slug . '-category') === false):
        ?>
            <select id="orderby" name="orderby" class="orderby" aria-label="Shop order" onchange="window.location = location.origin + this.value()">
                <option value="menu_order" <?=(!isset($_GET['orderby']) || $_GET['orderby'] == 'menu_order')?'selected="selected"':''?>>Default sorting</option>
                <option value="popularity" <?=$_GET['orderby'] == 'popularity'?'selected="selected"':''?>>Sort by popularity</option>
                <option value="rating" <?=$_GET['orderby'] == 'rating'?'selected="selected"':''?>>Sort by average rating</option>
                <option value="date" <?=$_GET['orderby'] == 'date'?'selected="selected"':''?>>Sort by latest</option>
                <option value="price" <?=$_GET['orderby'] == 'price'?'selected="selected"':''?>>Sort by price: low to high</option>
                <option value="price-desc" <?=$_GET['orderby'] == 'price-desc'?'selected="selected"':''?>>Sort by price: high to low</option>
            </select>
        <?php
        endif;
        switch (1) {
            case $current_category->slug == $parent->slug . '-store':
                $body_class = 'vendor-store';
                echo '<link rel="stylesheet" href="/wp-content/themes/storefront-child/assets/css/vendor-store.css" media="all">';
                include_once ABSPATH . 'wp-content/themes/storefront-child/vendor-store.php';
                echo '<script src="' . get_stylesheet_directory_uri() . '/assets/js/vendor-store.js"></script>';
                break;
            case $current_category->slug == $parent->slug . '-product':
                $body_class = 'vendor-product';
                echo '<link rel="stylesheet" href="/wp-content/themes/storefront-child/assets/css/vendor-product.css" media="all">';
                include_once ABSPATH . 'wp-content/themes/storefront-child/vendor-product.php';
                echo '<script src="' . get_stylesheet_directory_uri() . '/assets/js/vendor-product.js"></script>';
                break;
            case $current_category->slug == $parent->slug . '-time-limit':
                $body_class = 'vendor-time-limit';
                echo '<link rel="stylesheet" href="/wp-content/themes/storefront-child/assets/css/vendor-product.css" media="all">';
                include_once ABSPATH . 'wp-content/themes/storefront-child/vendor-product.php';
                echo '<script src="' . get_stylesheet_directory_uri() . '/assets/js/vendor-product.js"></script>';
                break;
            case $current_category->slug == $parent->slug . '-new':
                $body_class = 'vendor-new';
                echo '<link rel="stylesheet" href="/wp-content/themes/storefront-child/assets/css/vendor-product.css" media="all">';
                include_once ABSPATH . 'wp-content/themes/storefront-child/vendor-product.php';
                echo '<script src="' . get_stylesheet_directory_uri() . '/assets/js/vendor-product.js"></script>';
                break;
            case strpos($current_category->slug, $parent->slug . '-category') !== false :
                $body_class = 'vendor-category';
                echo '<script src="' . get_stylesheet_directory_uri() . '/assets/js/vendor-category.js"></script>';
                echo '<link rel="stylesheet" href="/wp-content/themes/storefront-child/assets/css/vendor-category.css" media="all">';
                include_once ABSPATH . 'wp-content/themes/storefront-child/vendor-category.php';
                break;
            default:
                $body_class = 'other-vendor';
                echo '<link rel="stylesheet" href="/wp-content/themes/storefront-child/assets/css/other-vendor.css" media="all">';
                include_once ABSPATH . 'wp-content/themes/storefront-child/vendor-product.php';
                echo '<script src="' . get_stylesheet_directory_uri() . '/assets/js/other-vendor.js"></script>';
        }
    } else if (
        ( sizeof(explode('/', $_SERVER['REQUEST_URI'])) <= 4 ) ||
        ( in_array('doweing-special-plan', explode('/', $_SERVER['REQUEST_URI'])) )
    ) {
        # Homepage-Child
        $body_class = 'other-archive-product';
        echo '<link rel="stylesheet" href="/wp-content/themes/storefront-child/assets/css/other-archive-product.css" media="all">';
        include_once ABSPATH . 'wp-content/themes/storefront-child/other-archive-product.php';
        echo '<script src="' . get_stylesheet_directory_uri() . '/assets/js/other-archive.js"></script>';
    } else {
        ?>
        <script>
            jQuery(function($){
                alert('無效頁面!即將回到首頁...');
                window.location = location.origin;
            })
        </script>
        <?php
//        header("Location: " . home_url());
    }
    ?>
        <script>
            jQuery(function($){
                $('body').addClass('<?=$body_class?>');
            })
        </script>
    <?php
    /**
     * Hook: woocommerce_after_main_content.
     *
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action( 'woocommerce_after_main_content' );

    /**
     * Hook: woocommerce_sidebar.
     *
     * @hooked woocommerce_get_sidebar - 10
     */
    do_action( 'woocommerce_sidebar' );

    get_footer( 'shop' );

elseif ($is_search):
    echo '<link rel="stylesheet" href="/wp-content/themes/storefront-child/assets/css/original-wc-archive.css" media="all">';
    echo '<script src="' . get_stylesheet_directory_uri() . '/assets/js/original-wc-archive.js"></script>';
    include_once ABSPATH . 'wp-content/themes/storefront-child/original-archive-product.php';
else:
    if ( woocommerce_product_loop() ) {
        /**
         * Hook: woocommerce_before_shop_loop.
         *
         * @hooked woocommerce_output_all_notices - 10
         * @hooked woocommerce_result_count - 20
         * @hooked woocommerce_catalog_ordering - 30
         */
        do_action( 'woocommerce_before_shop_loop' );

        woocommerce_product_loop_start();
        if ( wc_get_loop_prop( 'total' ) ) {
            while ( have_posts() ) {
                the_post();

                /**
                 * Hook: woocommerce_shop_loop.
                 */
                do_action( 'woocommerce_shop_loop' );

                wc_get_template_part( 'content', 'product' );
            }
        }

        woocommerce_product_loop_end();

        /**
         * Hook: woocommerce_after_shop_loop.
         *
         * @hooked woocommerce_pagination - 10
         */
        do_action( 'woocommerce_after_shop_loop' );
    } else {
        /**
         * Hook: woocommerce_no_products_found.
         *
         * @hooked wc_no_products_found - 10
         */
        do_action('woocommerce_no_products_found');

        /**
         * Hook: woocommerce_after_main_content.
         *
         * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
         */
        do_action( 'woocommerce_after_main_content' );

        /**
         * Hook: woocommerce_sidebar.
         *
         * @hooked woocommerce_get_sidebar - 10
         */
        do_action( 'woocommerce_sidebar' );

        get_footer( 'shop' );
    }
endif;


