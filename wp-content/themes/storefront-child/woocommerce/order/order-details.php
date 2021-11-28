<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.6.0
 */

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $order ) {
	return;
}

echo '<link rel="stylesheet" href="/wp-content/themes/storefront-child/assets/css/order-details.css" media="all">';

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
$order_number = $order->get_order_number();
$order_status = wc_get_order_status_name( $order->get_status() );
$order_created_date = $order->get_date_created()->date( 'Y-m-d' );
?>
<div class="account-order-detail-header">
    <div class="order-number">
        <div class="order-number-title">訂單編號</div>
        <div class="order-number-content"><?=$order_number?></div>
    </div>
    <div class="order-created-date">發布日期 <?=$order_created_date?></div>
</div>
<div class="account-order-detail-main-1">

    <div class="order-status-title">訂單狀態</div>
    <div class="order-status-content"><?=$order_status?></div>
    <?php
    if ( $show_customer_details ) {
        wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
    }
    ?>
</div>
<div class="account-order-detail-main-2">
    <section class="woocommerce-order-details">
        <?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>


        <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

            <thead>
                <tr>
                    <th class="woocommerce-table__product-name product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                    <th class="woocommerce-table__product-table product-total"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                do_action( 'woocommerce_order_details_before_order_table_items', $order );

                foreach ( $order_items as $item_id => $item ) {
                    $product = $item->get_product();

                    wc_get_template(
                        'order/order-details-item.php',
                        array(
                            'order'              => $order,
                            'item_id'            => $item_id,
                            'item'               => $item,
                            'show_purchase_note' => $show_purchase_note,
                            'purchase_note'      => $product ? $product->get_purchase_note() : '',
                            'product'            => $product,
                        )
                    );
                }

                do_action( 'woocommerce_order_details_after_order_table_items', $order );
                ?>
            </tbody>

            <tfoot>
                <?php
                foreach ( $order->get_order_item_totals() as $key => $total ) {
                    ?>
                        <tr>
                            <th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
                            <td><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
                        </tr>
                        <?php
                }
                ?>
                <?php if ( $order->get_customer_note() ) : ?>
                    <tr>
                        <th><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
                        <td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
                    </tr>
                <?php endif; ?>
            </tfoot>
        </table>

        <?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
    </section>
</div>
<?php
/**
 * Action hook fired after the order details.
 *
 * @since 4.4.0
 * @param WC_Order $order Order data.
 */
do_action( 'woocommerce_after_order_details', $order );

?>
<div class="account-order-detail-main-3">
    <h3>商品內容</h3>
    <?php
    foreach ( $order_items as $item_id => $item ) {
        $product = $item->get_product();
        $product_image_url = wp_get_attachment_url($product->get_image_id());
        $product_name = $product->get_name();
        $product_price = $product->get_price_html();
        $product_count = $item->get_quantity();
        ?>
        <div class="order-detail-item">
            <div class="order-detail-item-main">
                <img src="<?=$product_image_url?>">
                <div class="item">
                    <div class="item-name"><?=$product_name?></div>
                    <div class="item-price"><?=$product_price?></div>
                </div>
            </div>
            <div class="order-detail-item-amount">
                <div class="item-amount-title">數量</div>
                <div class="item-amount-number"><?=$product_count?></div>
            </div>
        </div>
        <?php
    }
?>
</div>
