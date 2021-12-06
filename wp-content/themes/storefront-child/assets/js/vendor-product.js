jQuery(function($) {

    $(document).ready(function() {
        $.each($('body.archive li.product'), function (key ,value) {
            if ($(value).find('a span.price span.woocommerce-Price-amount.amount').length == 1) {
                if ($(window ).width() <= 320) {
                    $(value).find('.price').css('cssText', 'margin-top: 19%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -40% !important;');
                } else if ($(window ).width() <= 390) {
                    $(value).find('.price').css('cssText', 'margin-top: 17%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -31% !important;');
                } else if ($(window ).width() <= 480) {
                    $(value).find('.price').css('cssText', 'margin-top: 13%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -26% !important;');
                } else if ($(window ).width() <= 768) {
                    $(value).find('.price').css('cssText', 'margin-top: 25%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -25% !important;');
                } else if ($(window ).width() <= 1024) {
                    $(value).find('.price').css('cssText', 'margin-top: 22%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -42% !important;');
                } else if ( $(window).width() > 1024) {
                    $(value).find('.price').css('cssText', 'margin-top: 20%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -37% !important;');
                }
            }
        })
    })

})
