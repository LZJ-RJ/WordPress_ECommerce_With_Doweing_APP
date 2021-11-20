jQuery(function ($) {
    $(document).ready(function(){

        $(window).resize(function() {
            autoSlick();
        });
    });

    autoSlick();

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
                slidesToShow: 2,
                rows: 1,
            });
        } else if ($(window ).width() > 320 && $(window ).width() <= 480) {
            $('.vendor-page-store-product-pd').slick({
                infinite: true,
                slidesToShow: 2,
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

    $("body.archive div.child-term-tab").on("mouseenter mouseleave", function (event) { //挷定滑鼠進入及離開事件
        if (event.type == "mouseenter") {
            $(this).css({"overflow-y": "scroll"}); //滑鼠進入
        } else {
            $(this).scrollTop(0).css({"overflow-y": "hidden"}); //滑鼠離開
        }
    });
})
