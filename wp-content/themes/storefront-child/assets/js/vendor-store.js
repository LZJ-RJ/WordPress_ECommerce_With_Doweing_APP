jQuery(function($) {

    $(document).ready(function() {
        $.each($('body.archive div.store-product-single-pd-cat-info'), function (key ,value) {
            if ($(value).find('span.price span.woocommerce-Price-amount.amount').length == 1) {
                if ($(window ).width() <= 320) {
                    $(value).find('.price').css('cssText', 'margin-top: 16%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -2px !important;');
                } else if ($(window ).width() <= 390) {
                    $(value).find('.price').css('cssText', 'margin-top: 14%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -32px !important;');
                } else if ($(window ).width() <= 480) {
                    $(value).find('.price').css('cssText', 'margin-top: 11%;');
                } else if ($(window ).width() <= 768) {
                    $(value).find('.price').css('cssText', 'margin-top: 17%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -9% !important;');
                } else if ($(window ).width() <= 1024) {
                    $(value).find('.price').css('cssText', 'margin-top: 13%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: 6% !important;');
                } else if ( $(window).width() > 1024) {
                    $(value).find('.price').css('cssText', 'margin-top: 11%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: 7% !important;');
                }
            } else {
                $(value).find('span.price').css('line-height', 1.3);
            }
        })
    })

})
