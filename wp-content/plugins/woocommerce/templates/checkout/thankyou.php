<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
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
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :

        ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<div>
                失敗的圖
            </div>

		<?php else : ?>
        <div class="wc-thank-you-info">
            <div class="wc-thank-you-img"></div>
            <div>
                <span class="wc-thank-you-successful">成功購買</span>
            </div>
            <span>5秒後自動跳轉</span>
            <div>
                <span class="wc-thank-you-look-up-order"><a href="<?php echo wc_get_account_endpoint_url('orders');?>">查看訂單&nbsp;→</a></span>
            </div>
        </div>
		<?php endif; ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

</div>
