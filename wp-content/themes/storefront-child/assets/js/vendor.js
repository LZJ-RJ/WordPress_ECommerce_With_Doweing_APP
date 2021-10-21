jQuery(function ($) {
    $(document).ready(function(){

        $(window).resize(function() {
            autoSlick();
            categoryMobileUI();
        });
    });

    categoryMobileUI();
    autoSlick();

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
                $('body.archive .vendor-category-sidebar').addClass('mobile-style-directory');
                $('body.archive .order-list').addClass('mobile-style-directory');
                $('body.archive #main > div.woocommerce').addClass('mobile-style-directory');
            } else if (urlPath.length > 5) {
                $('body.archive #main > div.woocommerce > ul > li.product.type-product').addClass('mobile-style-content')
                $('body.archive .vendor-category-sidebar').addClass('mobile-style-content');
                $('body.archive .order-list').addClass('mobile-style-content');
                $('body.archive #main > div.woocommerce').addClass('mobile-style-content');
                $('body.archive #main > div.woocommerce ul.products li.product img').addClass('mobile-style-content');
                $('body.archive #main > div.woocommerce ul.products li.product .woocommerce-loop-product__title').addClass('mobile-style-content');
                $('body.archive span.price').addClass('mobile-style-content');
                $('body.archive li.sale a.button.add_to_cart_button').addClass('mobile-style-content');
                $('body.archive a.button.add_to_cart_button').addClass('mobile-style-content');
            }
        }
    }
    function autoSlick() {
        if (  $('.vendor-page-store-product-pd.slick-initialized.slick-slider').length){
            $('.vendor-page-store-product-pd').slick('unslick');
        }

        if ($(window ).width() > 768) {
            $('.vendor-page-store-product-pd').slick({
                infinite: true,
                slidesToShow: 6,
                rows: 1,
            });
        } else if ($(window ).width() > 480 && $(window ).width() <= 768) {
            $('.vendor-page-store-product-pd').slick({
                infinite: true,
                slidesToShow: 3,
                rows: 1,
            });
        } else if ($(window ).width() > 320 && $(window ).width() <= 480) {
            $('.vendor-page-store-product-pd').slick({
                infinite: true,
                slidesToShow: 3,
                rows: 1,
            });
        } else if ($(window ).width() <= 320) {
            $('.vendor-page-store-product-pd').slick({
                infinite: true,
                slidesToShow: 2,
                rows: 1,
            });
        }
    }

    $('#orderby').on('change', function () {
        var url = $(this).val();
        if (url) {
            window.location =  location.origin + location.pathname + '?orderby=' + url;
        }
    });

    $('.order-list span.order-item').on('click', function() {
        window.location = location.origin + location.pathname + '?orderby=' + $(this).attr('attr-orderby');
    })

    var current_href = location.href;
    $.each($('.sidebar-item'), function (key, value){
        let sidebar_item_href = $(value).find('.item-link').attr('href');
        if ( current_href.includes(sidebar_item_href) ) {
            $(value).addClass('selected');
            $(value).find('.item-link').addClass('selected');
        }
    });
})
