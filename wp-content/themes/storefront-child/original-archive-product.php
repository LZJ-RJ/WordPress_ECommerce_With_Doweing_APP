<?php
echo '<h3>' . sprintf ( __( 'Search results for &ldquo;%s&rdquo;', 'woocommerce' ),  get_search_query()) . '</h3>';
?>
    <script>
        jQuery(function($){
            $('body').addClass('original-wc-archive');
        })
    </script>
<?php
global $post;
$search_product_list = array();

if ( woocommerce_product_loop() ) {
    /**
     * Hook: woocommerce_before_shop_loop.
     *
     * @hooked woocommerce_output_all_notices - 10
     * @hooked woocommerce_result_count - 20
     * @hooked woocommerce_catalog_ordering - 30
     */

    while ( have_posts() ) {


        if ( isset($is_vendor) && isset($parent) ) {
            if ( $is_vendor && product_is_in_category($parent->term_id, $post->ID)) {
                $search_product_list[] += $post->ID;
            } else {
                search_not_found();
            }
        } else {
            $search_product_list[] += $post->ID;
        }

        the_post();
        /**
         * Hook: woocommerce_shop_loop.
         */
    }

    if ( empty($search_product_list) ) {
        search_not_found();
        ?>
        <?php
    } else {
        echo do_shortcode('[products ids="' . implode(', ', $search_product_list) . '" paginate="1" per_page="36" columns="6" orderby="' . $_GET['orderby'] . '" order="asc" operator="in"]');
    }

    /**
     * Hook: woocommerce_after_shop_loop.
     *
     * @hooked woocommerce_pagination - 10
     */

} else {
    search_not_found();
}

function product_is_in_category($parent_category_id, $product = null) {
    $descendants = get_term_children((int)$parent_category_id, 'product_cat');
    if ($descendants && has_term($descendants, 'product_cat', $product)) {
        return true;
    }

    return false;
}

function search_not_found() {
    ?>
    <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'storefront' ); ?></h1>
    <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'storefront' ); ?></p>
    <?php
}

