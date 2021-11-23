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
            <div class="wc-thank-you-info">
                <div class="wc-thank-you-img-failed"></div>
                <div class="wc-thank-you-failed">
                    <span>購買未成功</span>
                </div>
                <div class="auto-redirect-text">5秒後自動跳轉</div>
                <div class="wc-thank-you-look-up-order">
                    <span><a href="<?php echo wc_get_cart_url()?>">回到購物車&nbsp;→</a></span>
                </div>
            </div>
		<?php else : ?>
            <div class="wc-thank-you-info">
                <div class="wc-thank-you-img-successful"></div>
                <div class="wc-thank-you-successful">
                    <span>成功購買</span>
                </div>
                <div class="auto-redirect-text">5秒後自動跳轉</div>
                <div class="wc-thank-you-look-up-order">
                    <span><a href="<?php echo wc_get_account_endpoint_url('orders');?>">查看訂單&nbsp;→</a></span>
                </div>
            </div>
		<?php endif; ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

</div>

<script>
    jQuery (function ($) {
        if ( $('body.woocommerce-page.woocommerce-checkout.woocommerce-order-received').length ) {
            setTimeout(function() {
                window.location.href = '<?=wc_get_account_endpoint_url('orders')?>';
            }, 5000);
        }
    });
</script>
