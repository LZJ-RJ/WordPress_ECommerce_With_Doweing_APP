<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders );

if ($_GET['status']) {
    $query_status = $_GET['status'] == 'on-hold' || $_GET['status'] == 'pending' ? ['on-hold', 'pending'] : [$_GET['status']];
} else {
    $query_status = ['on-hold', 'pending'];
}
$pending_orders = sizeof(wc_get_orders(['customer' => get_current_user_id(), 'limit' => -1, 'status' => ['on-hold', 'pending']]));
$processing_orders = sizeof(wc_get_orders(['customer' => get_current_user_id(), 'limit' => -1, 'status' => ['processing']]));
$completed_orders = sizeof(wc_get_orders(['customer' => get_current_user_id(), 'limit' => -1, 'status' => ['completed']]));
$refunded_orders = sizeof(wc_get_orders(['customer' => get_current_user_id(), 'limit' => -1, 'status' => ['refunded']]));
?>
<div class="account-order-status-list">
    <a class="account-order-list-tab <?=$query_status == ['on-hold', 'pending']?'selected':''?>" href="<?=wc_get_account_endpoint_url('orders')?>?status=pending">等待付款<?=$pending_orders>0?'('.$pending_orders.')':''?></a>
    <a class="account-order-list-tab <?=$query_status == ['processing']?'selected':''?>" href="<?=wc_get_account_endpoint_url('orders')?>?status=processing">處理中<?=$processing_orders>0?'('.$processing_orders.')':''?></a>
    <a class="account-order-list-tab <?=$query_status == ['completed']?'selected':''?>" href="<?=wc_get_account_endpoint_url('orders')?>?status=completed">完成<?=$completed_orders>0?'('.$completed_orders.')':''?></a>
    <a class="account-order-list-tab <?=$query_status == ['refunded']?'selected':''?>" href="<?=wc_get_account_endpoint_url('orders')?>?status=refunded">已退費<?=$refunded_orders>0?'('.$refunded_orders.')':''?></a>
</div>
<?php
if ( $has_orders ) :
    foreach ( $customer_orders->orders as $customer_order ) {
        $order = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
        $the_first_pd_total = '0';
        $the_first_pd_img_url = '';
        $the_first_pd_name = '';
        foreach( $order->get_items() as $item_id => $item ) {
            $the_first_pd_total = $item->get_product()->get_price_html();
            $the_first_pd_img_url = wp_get_attachment_url($item->get_product()->get_image_id());
            $the_first_pd_name = $item->get_product()->get_name();
            break;
        }

        $order_number = $order->get_order_number();
        $order_created_date = $order->get_date_created()->date( 'Y-m-d' );
        $order_total = $order->get_formatted_order_total();
        $item_count = $order->get_item_count() - $order->get_item_count_refunded();
        $order_view_url = $order->get_view_order_url();
        $actions = wc_get_account_orders_actions( $order );
        $action = '';
        if ( ! empty( $actions ) ) {
            foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                $action .= '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
            }
        }
        ?>
        <div class="account-order-item">
            <div class="order-number-date">
                <div class="order-number-title">訂單編號</div>
                <div class="order-number-content"><?=$order_number?></div>
                <div class="order-date">
                    <div class="order-date-title">發布日期: </div>
                    <div class="order-date-content"><?=$order_created_date?></div>
                </div>
            </div>
            <div class="order-item-main">
                <img src="<?=$the_first_pd_img_url?>">
                <div>
                    <div class="product-title"><?=$the_first_pd_name?></div>
                    <div class="product-price"><?=$the_first_pd_total != '0' ? $the_first_pd_total : ''?></div>
                </div>
            </div>
            <div class="order-item-meta">
                <div class="count-product">
                    <div class="meta-title">商品數</div>
                    <div class="meta-content"><?=$item_count?>件</div>
                </div>
                <div class="order-total">
                    <div class="meta-title">訂單總價</div>
                    <div class="meta-content"><?=$order_total?></div>
                </div>
            </div>
        </div>
        <hr>
        <div class="order-item-link">
            <a href="<?=$order_view_url?>">訂單詳情</a>
        </div>
        <?php
    }
    ?>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

    <?php if ( 1 < $customer_orders->max_num_pages ) : ?>
    <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
        <?php if ( 1 !== $current_page ) : ?>
            <a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ).'?status='.$_GET['status']; ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
        <?php endif; ?>

        <?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
            <a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ).'?status='.$_GET['status']?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
		<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
