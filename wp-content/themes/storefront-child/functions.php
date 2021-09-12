<?php
/*
 Theme Name:   Storefront Child Theme
 Description:  This is storefront Child Theme
 Template:     storefront
 Version:      1.0.0
 Tags:         light, dark, two-columns, right-sidebar, responsive-layout, accessibility-ready
 Text Domain:  storefrontchild
*/

class StorefrontChildTheme {
    private static $instance;

    public function __construct() {
        load_child_theme_textdomain('storefrontchild', plugin_dir_path(__FILE__) . 'languages');
        $this->include_backend_files();
        $this->include_frontend_files();
        $this->register_hooks();
    }

    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function include_backend_files() {
        include_once WC_ABSPATH . 'includes/wc-template-functions.php';
    }

    private function include_frontend_files() {
        wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js');
        wp_enqueue_script('storefront-child-theme-js', get_stylesheet_directory_uri() . '/assets/js/storefront.js');
    }

    private function register_hooks() {
        add_action( 'woocommerce_single_product_summary',  'woocommerce_breadcrumb', 1);

        add_action( 'woocommerce_before_variations_form', [$this, 'add_variations_btn'], 10 ,1);
        add_action( 'woocommerce_before_quantity_input_field',  [$this, 'add_before_quantity_btn']);
        add_action( 'woocommerce_after_quantity_input_field',  [$this, 'add_after_quantity_btn']);
        add_action( 'woocommerce_after_single_product_summary',  [$this, 'add_vendor_information'], 8);
        add_filter( 'woocommerce_product_tabs', [$this, 'modify_pd_tab'], 90);
        add_filter( 'storefront_handheld_footer_bar_links', [$this, 'modify_footer_bar'], 10 ,1);
    }

    public function add_before_quantity_btn() {
        if (is_single()) {
            echo '<span class="word-pd-quantity">數量：</span><div class="wrap2 plus"><span class="word-plus">＋</span></div>';
        } else if (is_cart()) {
            echo '<div class="wrap2 plus"><span class="word-plus">＋</span></div>';
        }
    }

    public function add_after_quantity_btn() {
        if (is_single() || is_cart()) {
            echo '<div class="wrap2 sub"><span class="word-sub">-</span></div>';
        }
    }

    public function add_variations_btn($attributes) {
        global $product;
        $html = '<span class="single-pd-spec-text">商品選項</span><hr>';
        foreach($attributes as $attribute_name => $options) {
            $html .=  '<div class="single-pd-spec-option"><div class="single-pd-spec-option-title"><span class="word-pd-attr-name">' . wc_attribute_label($attribute_name) . '：</span></div>';
            $terms = wc_get_product_terms(
                $product->get_id(),
                $attribute_name,
                array(
                    'fields' => 'all',
                )
            );

            $html .= '<div class="single-pd-spec-option-content">';
            foreach ( $terms as $term ) {
                if ( in_array( $term->slug, $options, true ) ) {
                    $html .= '<div class="box variation-box-normal"><span class="info variation-text-normal" attribute-term-taxonomy="' . $term->taxonomy . '" attribute-term-value="' . $term->slug . '">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute_name, $product ) ) . '</span></div>';
                }
            }
            $html .= '</div></div><br>';

            if ($attribute_name == 'pa_color') {
                $html .= '<br><br>';
            }
        }
        echo $html;
    }

    // Determine the top-most parent of a term
    private function get_term_top_most_parent( $term, $taxonomy = "" ) {
        $parent  = get_term( $term, $taxonomy );
        while ( $parent->parent != '0' ) {
            $term_id = $parent->parent;
            $parent  = get_term( $term_id, $taxonomy);
        }
        return $parent;
    }

    public function add_vendor_information() {
        global $post;
        $terms = get_the_terms( $post->ID, 'product_cat' );
        foreach ($terms as $term) {
            $topParentTerm = $this->get_term_top_most_parent($term, 'product_cat');
        }
        $thumbnail_id = get_term_meta( $topParentTerm->term_id, 'thumbnail_id', true );
        $imageUrl = wp_get_attachment_url( $thumbnail_id );
        $link = get_term_link( $topParentTerm->term_id, 'product_cat' );
        echo '
              <a class="single-pd-cat-info" href="'.$link.'">
                <img
                  class="single-pd-cat-thumbnail"
                  referrerpolicy="no-referrer"
                  src="'.$imageUrl.'"
                />
                <div class="single-pd-cat-text">
                  <span class="single-pd-cat-name">'.
                    $topParentTerm->name.
                  '</span>
                  <span class="single-pd-cat-des">'.
                    $topParentTerm->description.
                  '</span>
                </div>
              </a>
        ';
    }

    public function modify_pd_tab( $tabs ) {
        unset( $tabs['additional_information'] );
        $tabs['pd_spec'] = array(
            'title' 	=> __( '商品規格', 'storefrontchild' ),
            'priority' 	=> 15,
            'callback' 	=> [$this, 'single_pd_tab_pd_spec']
        );

        return $tabs;
    }

    public function single_pd_tab_pd_spec() {
        global $post;
        echo '<h2>'.__( '商品規格', 'storefrontchild' ).'</h2><hr>'.
            '<div class="tab-pd-wrapper spec"><div class="tab-pd-content">' . get_post_meta($post->ID, 'product_spec', 1) . '</div></div>';
    }

    public function modify_footer_bar($links) {
        $my_account = $links['my-account'];
        unset($links['my-account']);
        $links['my-account'] = $my_account;
        return $links;
    }
}

$GLOBALS['StorefrontChildTheme'] = StorefrontChildTheme::get_instance();
