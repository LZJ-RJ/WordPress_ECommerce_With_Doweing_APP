jQuery(function ($) {
    $(document).ready(function(){
        $(window).resize(function() {
            autoSlick();
        });
    });

    autoSlick();

    function autoSlick() {
        if (  $('.home-page-hot-channel-pd.slick-initialized.slick-slider').length){
            $('.home-page-hot-channel-pd').slick('unslick');
        }
        if (  $('.home-page-hot-product-pd.slick-initialized.slick-slider').length){
            $('.home-page-hot-product-pd').slick('unslick');
        }
        if (  $('.home-page-doweing-category-pd.slick-initialized.slick-slider').length){
            $('.home-page-doweing-category-pd').slick('unslick');
        }
        if (  $('.home-page-recommend-product-pd.slick-initialized.slick-slider').length){
            $('.home-page-recommend-product-pd').slick('unslick');
        }
        if ($(window ).width() > 480) {
            $('.home-page-hot-channel-pd').slick({
                infinite: true,
                slidesToShow: 7,
                rows: 1,
            });
            $('.home-page-hot-product-pd').slick({
                infinite: true,
                slidesToShow: 7,
                rows: 1,
            });
            $('.home-page-doweing-category-pd').slick({
                infinite: true,
                slidesPerRow: 7,
                rows: 2,
            });
            $('.home-page-recommend-product-pd').slick({
                infinite: true,
                slidesPerRow: 7,
                rows: 2,
            });
        } else if ($(window ).width() > 320 && $(window ).width() <= 480) {
            $('.home-page-hot-channel-pd').slick({
                infinite: true,
                slidesToShow: 2,
                rows: 1,
            });
            $('.home-page-hot-product-pd').slick({
                infinite: true,
                slidesToShow: 2,
                rows: 1,
            });
            $('.home-page-doweing-category-pd').slick({
                infinite: true,
                slidesToShow: 2,
                rows: 2,
            });
            $('.home-page-recommend-product-pd').slick({
                infinite: true,
                slidesToShow: 2,
                rows: 1
            })
        } else if ($(window ).width() <= 320) {
            $('.home-page-hot-channel-pd').slick({
                infinite: true,
                slidesToShow: 2,
                rows: 1,
            });
            $('.home-page-hot-product-pd').slick({
                infinite: true,
                slidesToShow: 2,
                rows: 1,
            });
            $('.home-page-doweing-category-pd').slick({
                infinite: true,
                slidesToShow: 2,
                rows: 2,
            });
            $('.home-page-recommend-product-pd').slick({
                infinite: true,
                slidesToShow: 2,
                rows: 1
            })
        }

        $('.home-page-hot-channel-pd, .home-page-hot-product-pd, ' +
            '.home-page-doweing-category-pd, .home-page-recommend-product-pd').find('a').addClass('home-all-pd-slider-single-parent');

        $('.home-page-hot-channel-pd, .home-page-hot-product-pd, ' +
            '.home-page-doweing-category-pd, .home-page-recommend-product-pd').find('p').addClass('home-all-pd-slider-text');

        $('.home-page-doweing-category-pd').find('p').addClass('home-category-pd-slider-text');


    }
})
