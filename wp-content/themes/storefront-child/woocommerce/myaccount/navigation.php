<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );

$profile_img_id = get_user_meta(get_current_user_id(), 'wp_metronet_image_id', 1);
$imageUrl = wp_get_attachment_url($profile_img_id);
$user = get_user_by('id', get_current_user_id());
$user_email = $user->user_email;
$user_display_name = $user->display_name;
?>
<nav class="woocommerce-MyAccount-navigation">
    <div class="account-personal-info">
        <img
                class="account-thumbnail"
                referrerpolicy="no-referrer"
                src="<?=$imageUrl?>"
        />
        <div class="account-info-text">
                  <span class="account-info-name">
            <?=$user_display_name?>
            </span>
            <span class="account-info-email">
           <?=$user_email?>
            </span>
        </div>
    </div>
	<ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
