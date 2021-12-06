jQuery(function($){

    $(document).ready(function() {
        $.each($('body.archive.other-archive-product li.product'), function (key ,value) {
            if ($(value).find('a span.price span.woocommerce-Price-amount.amount').length == 1) {
                if ($(window ).width() <= 320) {
                    $(value).find('.price').css('cssText', 'margin-top: 15%;');
                } else  if ($(window ).width() <= 390) {
                    $(value).find('.price').css('cssText', 'margin-top: 11%;');
                } else if ($(window ).width() <= 480) {
                    $(value).find('.price').css('cssText', 'margin-top: 9%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: 10px !important;');
                } else if ($(window ).width() <= 768) {
                    $(value).find('.price').css('cssText', 'margin-top: 18%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -23% !important;');
                } else if ($(window).width() <= 1024) {
                    $(value).find('.price').css('cssText', 'margin-top: 13% !important;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -43% !important;');
                } else if ($(window).width() > 1024) {
                    $(value).find('.price').css('cssText', 'margin-top: 14%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -38% !important;');
                }
            } else {
                $(value).find('a span.price').css('line-height', 1.3);
            }
        })
    })

})
