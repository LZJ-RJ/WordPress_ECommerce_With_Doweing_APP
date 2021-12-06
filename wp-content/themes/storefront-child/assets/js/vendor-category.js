jQuery(function ($) {
    $(document).ready(function(){
        $(window).resize(function() {
            categoryMobileUI();
        });
    });

    categoryMobileUI();
    function categoryMobileUI() {
        $('body.archive .vendor-category-sidebar').removeClass('mobile-style-directory');
        $('body.archive .order-list').removeClass('mobile-style-directory');
        $('body.archive #main > div.woocommerce').removeClass('mobile-style-directory');
        $('body.archive .vendor-category-sidebar').removeClass('mobile-style-content');
        $('body.archive .order-list').removeClass('mobile-style-content');
        $('body.archive #main > div.woocommerce').removeClass('mobile-style-content');
        $('body.archive #main > div.woocommerce > ul > li.product.type-product').removeClass('mobile-style-content')
        $('body.archive #main > div.woocommerce ul.products li.product img').removeClass('mobile-style-content')
        $('body.archive #main > div.woocommerce ul.products li.product .woocommerce-loop-product__title').removeClass('mobile-style-content')
        $('body.archive span.price').removeClass('mobile-style-content')
        $('body.archive li.sale a.button.add_to_cart_button').removeClass('mobile-style-content')
        $('body.archive a.button.add_to_cart_button').removeClass('mobile-style-content')

        var urlPath = location.pathname.split('/');
        if ($(window).width() <= 480 &&  urlPath[3] == urlPath[2] + '-category') {
            if (urlPath.length == 5) {
                // vendor-分類目錄
                $('body.archive .vendor-category-sidebar').addClass('mobile-style-directory');
                $('body.archive .order-list').addClass('mobile-style-directory');
                $('body.archive #main > div.woocommerce').addClass('mobile-style-directory');
            } else if (urlPath.length > 5) {
                // vendor-分類內頁-顯示商品
                $('body.archive #main > div.woocommerce > ul > li.product.type-product').addClass('mobile-style-content')
                $('body.archive .vendor-category-sidebar').addClass('mobile-style-content');
                $('body.archive #main > div.woocommerce').addClass('mobile-style-content');
                $('body.archive #main > div.woocommerce ul.products li.product img').addClass('mobile-style-content');
                $('body.archive #main > div.woocommerce ul.products li.product .woocommerce-loop-product__title').addClass('mobile-style-content');
                $('body.archive span.price').addClass('mobile-style-content');
                $('body.archive li.sale a.button.add_to_cart_button').addClass('mobile-style-content');
                $('body.archive a.button.add_to_cart_button').addClass('mobile-style-content');
            }
        }
    }

    var current_href = location.href;
    $.each($('.sidebar-item'), function (key, value){
        let sidebar_item_href = $(value).find('.item-link').attr('href');
        if ( current_href.includes(sidebar_item_href) ) {
            $(value).addClass('selected');
            $(value).find('.item-link').addClass('selected');
        }
    });

    $('body').on('click', '.sidebar-item', function (){
        location.href = $(this).find('.item-link').attr('href');
    });

    $(document).ready(function() {
        $.each($('body.archive.vendor-category li.product'), function (key ,value) {
            if ($(value).find('a span.price span.woocommerce-Price-amount.amount').length == 1) {
                if ($(window ).width() <= 320) {
                    $(value).find('.price').css('cssText', 'margin-top: 19%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -38% !important;');
                } else if ($(window ).width() <= 390) {
                    $(value).find('.price').css('cssText', 'margin-top: 15%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -31% !important;');
                } else if ($(window ).width() <= 480) {
                    $(value).find('.price').css('cssText', 'margin-top: 11%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -27% !important;');
                } else if ($(window ).width() <= 768) {
                    $(value).find('.price').css('cssText', 'margin-top: 26%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -22% !important;');
                } else if ($(window ).width() <= 1024) {
                    $(value).find('.price').css('cssText', 'margin-top: 27%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -39% !important;');
                } else if ( $(window).width() > 1024) {
                    $(value).find('.price').css('cssText', 'margin-top: 23%;');
                    $(value).find('a.button.add_to_cart_button').css('cssText', 'margin-top: -33% !important;');
                }
            }
        })
    })

})
