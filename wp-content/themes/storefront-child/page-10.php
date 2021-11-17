<?php
get_header();
// ************************HomePage Slider************************
echo do_shortcode('[smartslider3 slider="2"]');
?>
    <!--************************特別企劃************************-->
<?php
$terms = get_terms(array(
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    'parent' => 38,
    'limit' => -1,
));
?>
    <div class="home-page-special-plan">
        <?php
        foreach ($terms as $term) {
            $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
            $imageUrl = wp_get_attachment_url($thumbnail_id);
            $link = get_term_link($term->term_id, 'product_cat');
            ?>
            <a class="special-plan-single-pd-cat-info" href="<?= $link ?>">
                <img
                        class="single-pd-cat-thumbnail"
                        referrerpolicy="no-referrer"
                        src="<?= $imageUrl ?>"
                />
                <span><?= $term->name ?></span>
            </a>
            <?php
        }
        ?>
    </div>
    <!--************************熱門頻道商城************************-->
<?php
$hot_channel_category_id = 39;
$hot_channel_terms = unserialize(get_option('doweing_hot_channel'));
?>
    <div class="home-page-hot-channel">
        <div class="header">
            <h3>熱門頻道商城</h3>
        </div>
        <div class="home-page-hot-channel-pd">
            <?php
            foreach ($hot_channel_terms as $term_id) {
                $tmp_term = get_term($term_id, "product_cat");
                $thumbnail_id = get_term_meta($tmp_term->term_id, 'thumbnail_id', true);
                $imageUrl = wp_get_attachment_image_src($thumbnail_id, "Medium")[0];
                $link = get_term_link($tmp_term->term_id, 'product_cat');
                ?>
                <a class="hot-channel-single-pd-cat-info" href="<?= $link ?>">
                    <img
                            class="hot-channel-single-pd-cat-thumbnail"
                            referrerpolicy="no-referrer"
                            src="<?= $imageUrl ?>"
                    />
                    <span><?= $tmp_term->name ?></span>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
    <!--************************熱門商品************************-->
<?php
$hot_product_category_id = 40;
$hot_products = wc_get_products(array(
    'limit' => -1,
    'post_status' => "publish",
    'category' => array(get_term($hot_product_category_id)->slug)
));
if (count($hot_products) > 7) {
    $is_more = true;
    $more_link = get_term_link($hot_product_category_id, 'product_cat');
} else {
    $is_more = false;
}
?>
    <div class="home-page-hot-product">
        <div class="header">
            <h3>熱門商品</h3>
            <span>
                <?= ($is_more) ? '<a href="' . $more_link . '">更多</a>' : '' ?>
            </span>
        </div>
        <div class="home-page-hot-product-pd">
            <?php
            foreach ($hot_products as $product) {
                $thumbnail_id = $product->get_image_id();
                $imageUrl = wp_get_attachment_url($thumbnail_id);
                $link = get_permalink($product->get_id());
                ?>
                <a class="hot-product-single-pd-cat-info" href="<?= $link ?>">
                    <img
                            class="hot-product-single-pd-cat-thumbnail"
                            referrerpolicy="no-referrer"
                            src="<?= $imageUrl ?>"
                    />
                    <span><?= $product->get_name() ?></span>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
    <!--************************分類************************-->
<?php
$doweing_category_id = 41;
$doweing_category = unserialize(get_option('doweing_category'));
?>
    <div class="home-page-doweing-category">
        <div class="header">
            <h3>分類</h3>
        </div>
        <div class="home-page-doweing-category-pd">
            <?php
            foreach ($doweing_category as $term_id) {
                $tmp_term = get_term($term_id, "product_cat");
                $thumbnail_id = get_term_meta($tmp_term->term_id, 'thumbnail_id', true);
                $imageUrl = wp_get_attachment_image_src($thumbnail_id, "Medium")[0];
                $link = get_term_link($tmp_term->term_id, 'product_cat');
                ?>
                <a class="doweing-category-single-pd-cat-info" href="<?= $link ?>">
                    <img
                            class="doweing-category-single-pd-cat-thumbnail"
                            referrerpolicy="no-referrer"
                            src="<?= $imageUrl ?>"
                    />
                    <span><?= $tmp_term->name ?></span>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
    <!--************************推薦商品************************-->
<?php
$recommend_product_category_id = 42;
$recommend_products = wc_get_products(array(
    'limit' => -1,
    'post_status' => "publish",
    "category" => array(get_term($recommend_product_category_id)->slug)
));
if (count($recommend_products) > 14) {
    $is_more = true;
    $more_link = get_term_link($recommend_product_category_id, 'product_cat');
} else {
    $is_more = false;
}
?>
    <div class="home-page-recommend-product">
        <div class="header">
            <h3>推薦商品</h3>
            <span>
                <?= ($is_more) ? '<a href="' . $more_link . '">更多</a>' : '' ?>
            </span>
        </div>
        <div class="home-page-recommend-product-pd">
            <?php
            foreach ($recommend_products as $product) {
                $thumbnail_id = $product->get_image_id();
                $imageUrl = wp_get_attachment_url($thumbnail_id);
                $link = get_permalink($product->get_id());
                ?>
                <a class="recommend-product-single-pd-cat-info" href="<?= $link ?>">
                    <img
                            class="recommend-product-single-pd-cat-thumbnail"
                            referrerpolicy="no-referrer"
                            src="<?= $imageUrl ?>"
                    />
                    <span><?= $product->get_name() ?></span>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
<?php
get_footer();
