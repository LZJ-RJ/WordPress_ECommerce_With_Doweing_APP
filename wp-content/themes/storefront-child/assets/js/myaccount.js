jQuery(function($) {
    // $('body').off('click', '.global-page-return');

    $('ul li.woocommerce-MyAccount-navigation-link.is-active').addClass('navigation-selected');

    window.onload = function () {
        if ($(window).width() <= 480) {
            if ($('.account-order-detail-main-2').length && $('.account-order-detail-main-3').length) {
                let main_2_html = $('.account-order-detail-main-2');
                $('.account-order-detail-main-2').remove();
                $(main_2_html).insertAfter($('.account-order-detail-main-3'));
            }
        }
    }

});
