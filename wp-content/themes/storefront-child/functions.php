<?php
/*
 Theme Name:   Storefront Child Theme
 Description:  This is storefront Child Theme
 Template:     storefront
 Version:      1.0.0
 Tags:         light, dark, two-columns, right-sidebar, responsive-layout, accessibility-ready
 Text Domain:  storefrontchild
*/

class StorefrontChildTheme
{
    private static $instance;

    public function __construct()
    {
        load_child_theme_textdomain('storefrontchild', plugin_dir_path(__FILE__) . 'languages');
        $this->include_backend_files();
        $this->include_frontend_files();
        $this->register_hooks();
    }

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function include_backend_files()
    {
        include_once WC_ABSPATH . 'includes/wc-template-functions.php';
        wp_enqueue_style('admin-css', get_stylesheet_directory_uri() . '/assets/css/admin.css?version=20211127');
    }
    private function include_frontend_files()
    {
        $is_admin_page = is_admin();
        if (!$is_admin_page) {
            wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js?version=20211127');
            wp_enqueue_script('storefront-child-theme-js', get_stylesheet_directory_uri() . '/assets/js/storefront.js?version=20211127');
            wp_enqueue_style('storefront-child-theme-css', get_stylesheet_directory_uri() . '/assets/css/storefront.css?version=20211127');
        }
     }

    private function register_hooks()
    {
        add_action('wp_footer', [$this, 'include_files_in_footer'], 1);

        add_action('woocommerce_before_variations_form', [$this, 'add_variations_btn'], 10, 1);
        add_action('woocommerce_before_quantity_input_field', [$this, 'add_before_quantity_btn']);
        add_action('woocommerce_after_quantity_input_field', [$this, 'add_after_quantity_btn']);
        add_action('woocommerce_after_single_product_summary', [$this, 'add_vendor_information'], 8);
        add_filter('woocommerce_product_tabs', [$this, 'modify_pd_tab'], 90);
        add_filter('storefront_handheld_footer_bar_links', [$this, 'modify_footer_bar'], 10, 1);
        add_action('admin_menu', [$this, 'add_menu_page']);
        add_filter('wp_head', [$this, 'redirect_to_somewhere']);

        add_action( 'init', [$this, 'my_custom_endpoints'] );
        add_filter( 'woocommerce_account_menu_items', [$this, 'my_custom_my_account_menu_items']);
        add_action('woocommerce_account_my-wishlist_endpoint', [$this, 'my_custom_endpoint_content']);
        add_filter( 'query_vars', [$this, 'my_custom_query_vars'], 0 );
        add_action( 'wp_loaded', [$this, 'my_custom_flush_rewrite_rules']);

        add_action( 'storefront_before_header', [$this, 'add_return_icon']);
        add_filter( 'woocommerce_add_to_cart_validation', [$this, 'handle_is_verified'], 99, 3); //加入購物車前的驗證
        add_filter( 'woocommerce_my_account_my_orders_query', [$this, 'add_url_query_status'], 999, 1);

        add_action('create_product_cat', [$this, 'add_author'], 10, 1);
        add_filter('pre_get_posts', [$this, 'posts_for_current_author']);
   }

   public function add_author($taxonomy_id) {
        add_term_meta($taxonomy_id, '_author_id', wp_get_current_user()->ID);
   }

    public function posts_for_current_author($query) {
        $user = wp_get_current_user();
        $is_admin_page = is_admin();
        if ( !in_array( 'admin', (array) $user->roles ) &&
            $is_admin_page
        ) {
            if( !current_user_can( 'edit_others_posts' ) ) {
                $query->set('author', $user->ID );

            }
            return $query;
        }
    }

    public function add_url_query_status($args) {
        if ($_GET['status']) {
            $query_status = $_GET['status'] == 'on-hold' || $_GET['status'] == 'pending' ? ['on-hold', 'pending'] : [$_GET['status']];
        } else {
            $query_status = ['on-hold', 'pending'];
        }
        $args['status'] = $query_status;

        return $args;
    }

    public function handle_is_verified($result = true, $product_id, $quantity) {
        global $woocommerce;
        $product_category = wc_get_product($product_id)->get_category_ids();
        $vendor_category_name = '';
        foreach ($product_category as $term_id) {
            $tmp_term = get_term($term_id, "product_cat");
            if ($tmp_term->parent == 0 && strlen($tmp_term->slug) >= 8 && is_numeric(substr($tmp_term->slug, 7))) {
                $vendor_category_name = $tmp_term->slug;
            }
        }

        if ( !empty($woocommerce->cart->cart_contents) && $vendor_category_name != '' )  {
            $old_vendor_category_name = '';
            foreach ($woocommerce->cart->cart_contents as $item => $value) {
                $product_category = wc_get_product($woocommerce->cart->cart_contents[$item]['product_id'])->get_category_ids();
                foreach ($product_category as $term_id) {
                    $tmp_term = get_term($term_id, "product_cat");
                    if ($tmp_term->parent == 0 && strlen($tmp_term->slug) >= 8 && is_numeric(substr($tmp_term->slug, 7))) {
                        $old_vendor_category_name = $tmp_term->slug;
                    }
                }

               if ($old_vendor_category_name != $vendor_category_name) {
                    wc_add_notice('請先把購物車原本的商品移除，否則無法加入，因為一次只能購買一個頻道主的商品。','notice');
//                    unset($woocommerce->cart->cart_contents[$item]);
                    $result = false;
                    break;
                }
            }
        }

        return $result;
    }

    public function add_return_icon() {
    ?>
        <a class="global-page-return" href="#"><i class="fas fa-chevron-left"></i></a>
        <?php
    }

    public function my_custom_flush_rewrite_rules() {
        flush_rewrite_rules();
    }

    public function my_custom_query_vars( $vars ) {
        $vars[] = 'my-wishlist';

        return $vars;
    }

    public function my_custom_endpoints() {
        add_rewrite_endpoint( 'my-wishlist', EP_ROOT | EP_PAGES );
    }

    public function my_custom_endpoint_content( $current_page ) {
        $current_page    = empty( $current_page ) ? 1 : absint( $current_page );
        include_once ABSPATH . 'wp-content/themes/storefront-child/woocommerce/myaccount/my-wishlist.php';
    }

    public function my_custom_my_account_menu_items($items) {
        $items = array(
//            'dashboard'         => __( 'Dashboard', 'woocommerce' ),
            'orders'            => __( 'Orders', 'woocommerce' ),
            //'downloads'       => __( 'Downloads', 'woocommerce' ),
            'edit-account'      => __( 'Edit Account', 'woocommerce' ),
            'edit-address'    => __( 'Addresses', 'woocommerce' ),
            //'payment-methods' => __( 'Payment Methods', 'woocommerce' ),
            'my-wishlist'      => __( 'Wishlist', 'woocommerce' ),
            'customer-logout'   => __( 'Logout', 'woocommerce' ),
        );

        return $items;
    }

    public function redirect_to_somewhere(){
        if ($_SERVER['REQUEST_URI'] == '/shop/') {
            header("Location: ".home_url());
        }

        if ($_SERVER['REQUEST_URI'] == '/my-account/') {
            header("Location: ".home_url()."/my-account/orders/");
        }
    }

    public function setting_doweing_category()
    {
        if ($_POST['from'] == 'setting_doweing_hot_channel') {
            $post_data = $_POST['doweing_hot_channel'];
            if (!empty($post_data)) {
                update_option('doweing_hot_channel', serialize($post_data));
            }
        } else if ($_POST['from'] == 'setting_doweing_category') {
            $post_data = $_POST['doweing_category'];
            if (isset($post_data)) {
                update_option('doweing_category', serialize($post_data));
            }
        }
        ?>
        <script src="<?= get_stylesheet_directory_uri() ?>/assets/js/setting_doweing_store_pd_cat.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <h3>設定Doweing 熱門頻道主</h3>
        <div>
            <?php
            if (get_option('doweing_hot_channel') != '') {
                $explode_data = unserialize(get_option('doweing_hot_channel'));
                echo '總數:' . count($explode_data);
            ?>
                <table>
                    <thead>
                    <tr>
                        <td>ID</td>
                        <td>名稱</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($explode_data as $id) {
                        $tmp_term = get_term($id, "product_cat");
                        print("<tr>");
                        print("<td>" . $id . "</td>");
                        print("<td>" . $tmp_term->name . "</td>");
                        print("</tr>");
                    }
                    ?>
                    </tbody>
                </table>
                <?php
            } else {
                print('Doweing熱門頻道主的分類ID: 無<br>');
            }

            print('<br>');
            ?>
        </div>
        <form method="post">
            <input type="hidden" name="from" value="setting_doweing_hot_channel">
            <label for="doweing_hot_channel">Doweing的熱門頻道主的分類ID</label>
            <br>
            <select id="doweing_hot_channel" name="doweing_hot_channel[]" multiple="multiple" style="width:75%;">
                <?php
                global $wpdb;
                $result = $wpdb->get_results("SELECT `term_id`, `name` FROM `wp_terms` WHERE `slug` LIKE 'vendor-%' ORDER BY `slug` ASC");
                foreach ($result as $value) {
                    ?>
                    <option value="<?= $value->term_id ?>" <?= (in_array($value->term_id, $explode_data)) ? "selected" : "" ?>><?= $value->name ?></option>
                    <?php
                }
                ?>
            </select>
            <br>
            <input type="submit" value="送出">
        </form>
        <hr>
        <h3>設定Doweing 分類</h3>
        <div>
            <?php
            if (get_option('doweing_category') != '') {
                $explode_data = unserialize(get_option('doweing_category'));
                ?>
                <span>總數:<?=count($explode_data)?></span>
                <table>
                    <thead>
                    <tr>
                        <td>ID</td>
                        <td>名稱</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($explode_data as $id) {
                        $tmp_term = get_term($id, "product_cat");
                        print("<tr>");
                        print("<td>" . $id . "</td>");
                        print("<td>" . $tmp_term->name . "</td>");
                        print("</tr>");
                    }
                    ?>
                    </tbody>
                </table>
                <?php
            } else {
                print('Doweing分類ID: 無<br>');
            }
            ?>
        </div>
        <br>
        <form method="post">
            <input type="hidden" name="from" value="setting_doweing_category">
            <label for="doweing_category">Doweing的分類ID</label>
            <br>
            <select id="doweing_category" name="doweing_category[]" multiple="multiple" style="width:75%;">
                <?php
                $result = $wpdb->get_results("SELECT `term_id`, `name` FROM `wp_terms` WHERE `slug` LIKE '%vendor-%-category-%' ORDER BY `slug` ASC");
                foreach ($result as $value) {
                    ?>
                    <option value="<?= $value->term_id ?>" <?= (in_array($value->term_id, $explode_data)) ? "selected" : "" ?>><?= $value->name ?></option>
                    <?php
                }
                ?>
            </select>
            <br>
            <input type="submit" value="送出">
        </form>
        <?php
    }

    public function add_menu_page()
    {
        add_menu_page(
            '設定Doweing大分類',
            '設定Doweing大分類',
            'manage_options',
            'setting_doweing_category',
            array($this, 'setting_doweing_category'),
            '',
            2
        );
    }

    public function include_files_in_footer()
    {
        if (is_front_page()) {
            wp_enqueue_style('home-css', get_stylesheet_directory_uri() . '/assets/css/home.css?version=202111271545');
            wp_enqueue_script('home-js', get_stylesheet_directory_uri() . '/assets/js/home.js?version=20211127');
        }

        $category = get_queried_object();
        while ($category->parent != 0) {
            $category = get_term($category->parent);
        }

        if (strpos($_SERVER['REQUEST_URI'], '/my-account/') !== false) {
            wp_enqueue_style('myaccount-css', get_stylesheet_directory_uri() . '/assets/css/myaccount.css?version=20211127');
            wp_enqueue_script('myaccount-js', get_stylesheet_directory_uri() . '/assets/js/myaccount.js?version=20211127');
        }

        if (is_product()) {
            wp_enqueue_style('single-product-css', get_stylesheet_directory_uri() . '/assets/css/single-product.css?version=20211127');
            wp_enqueue_script('single-product-js', get_stylesheet_directory_uri() . '/assets/js/single-product.js?version=20211127');
        } else if (is_cart()) {
            wp_enqueue_style('cart-css', get_stylesheet_directory_uri() . '/assets/css/cart.css?version=20211127');
        } else if (is_checkout()) {
            wp_enqueue_style('checkout-css', get_stylesheet_directory_uri() . '/assets/css/checkout.css?version=20211127');
        }
    }

    public function add_before_quantity_btn()
    {
        if (is_single()) {
            echo '<span class="word-pd-quantity">數量：</span><div class="wrap2 plus"><span class="word-plus">＋</span></div>';
        } else if (is_cart()) {
            echo '<div class="wrap2 plus"><span class="word-plus">＋</span></div>';
        }
    }

    public function add_after_quantity_btn()
    {
        if (is_single() || is_cart()) {
            echo '<div class="wrap2 sub"><span class="word-sub">-</span></div>';
        }
    }

    public function add_variations_btn($attributes)
    {
        global $product;
        $html = '<span class="single-pd-spec-text">商品選項</span><hr>';
        foreach ($attributes as $attribute_name => $options) {
            $html .= '<div class="single-pd-spec-option"><div class="single-pd-spec-option-title"><span class="word-pd-attr-name">' . wc_attribute_label($attribute_name) . '：</span></div>';
            $terms = wc_get_product_terms(
                $product->get_id(),
                $attribute_name,
                array(
                    'fields' => 'all',
                )
            );

            $html .= '<div class="single-pd-spec-option-content">';
            foreach ($terms as $term) {
                if (in_array($term->slug, $options, true)) {
                    $html .= '<div class="box variation-box-normal"><span class="info variation-text-normal" attribute-term-taxonomy="' . $term->taxonomy . '" attribute-term-value="' . $term->slug . '">' . esc_html(apply_filters('woocommerce_variation_option_name', $term->name, $term, $attribute_name, $product)) . '</span></div>';
                }
            }
            $html .= '</div></div><br>';

            $html .= '<br><br>';

        }
        echo $html;
    }

    // Determine the top-most parent of a term
    private function get_term_top_most_parent($term, $taxonomy = "")
    {
        $parent = get_term($term, $taxonomy);
        while ($parent->parent != '0') {
            $term_id = $parent->parent;
            $parent = get_term($term_id, $taxonomy);
        }
        return $parent;
    }

    public function add_vendor_information()
    {
        global $post;
        $terms = get_the_terms($post->ID, 'product_cat');
        foreach ($terms as $term) {
            if (strpos($term->slug, 'vendor-') == 0) {
                $topParentTerm = $this->get_term_top_most_parent($term, 'product_cat');
                break;
            }
        }
        $thumbnail_id = get_term_meta($topParentTerm->term_id, 'thumbnail_id', true);
        $imageUrl = wp_get_attachment_url($thumbnail_id);
        $link = get_term_link($topParentTerm->term_id, 'product_cat');
        echo '
              <a class="single-pd-cat-info" href="' . $link . '">
                <img
                  class="single-pd-cat-thumbnail"
                  referrerpolicy="no-referrer"
                  src="' . $imageUrl . '"
                />
                <div class="single-pd-cat-text">
                  <span class="single-pd-cat-name">' .
            $topParentTerm->name .
            '</span>
                  <span class="single-pd-cat-des">' .
            $topParentTerm->description .
            '</span>
                </div>
              </a>
        ';
    }

    public function modify_pd_tab($tabs)
    {
        unset($tabs['additional_information']);
        $tabs['pd_spec'] = array(
            'title' => __('商品規格', 'storefrontchild'),
            'priority' => 15,
            'callback' => [$this, 'single_pd_tab_pd_spec']
        );

        return $tabs;
    }

    public function single_pd_tab_pd_spec()
    {
        global $post;
        echo '<h2>' . __('商品規格', 'storefrontchild') . '</h2><hr>' .
            '<div class="tab-pd-wrapper spec"><div class="tab-pd-content">' . get_post_meta($post->ID, 'product_spec', 1) . '</div></div>';
    }

    public function modify_footer_bar($links)
    {
        $my_account = $links['my-account'];
        unset($links['my-account']);
        $links['my-account'] = $my_account;
        return $links;
    }
}

$GLOBALS['StorefrontChildTheme'] = StorefrontChildTheme::get_instance();
