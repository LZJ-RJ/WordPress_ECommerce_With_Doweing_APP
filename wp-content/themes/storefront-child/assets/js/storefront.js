jQuery(function($){

    $('.single-product .box').on('mouseover', function () {
        // hover
        $(this)
            .removeClass('variation-box-normal variation-box-clicked');
        $(this)
            .addClass('variation-box-hover');

        $(this).find('.info')
            .removeClass('variation-text-normal variation-text-selected')
            .addClass('variation-text-hover');
    });

    $('.single-product .box').on('mouseout', function () {
            // normal
        if(!$(this).hasClass('selected')) {
            $(this)
                .removeClass('variation-box-hover variation-box-clicked')
                .addClass('variation-box-normal');

            $(this).find('.info')
                .removeClass('variation-text-hover variation-text-selected')
                .addClass('variation-text-normal');
        } else {
            // click
            $(this)
                .removeClass('variation-box-hover variation-box-normal')
                .addClass('variation-box-clicked');

            $(this).find('.info')
                .removeClass('variation-text-hover variation-text-normal')
                .addClass('variation-text-selected');
        }
    });

    $('.single-product .box').on('mouseup', function () {
        if(!$(this).hasClass('selected')) {
            $(this).parents('.single-pd-spec-option').find('.box')
                .removeClass('selected');

            // normal
            $(this).parents('.single-pd-spec-option').find('.box')
                .removeClass('variation-box-hover variation-box-clicked')
                .addClass('variation-box-normal');

            $(this).parents('.single-pd-spec-option').find('.box .info')
                .removeClass('variation-text-hover variation-text-selected')
                .addClass('variation-text-normal');

            // click
            $(this)
                .removeClass('variation-box-hover variation-box-normal')
                .addClass('variation-box-clicked');

            $(this).find('.info')
                .removeClass('variation-text-hover variation-text-normal')
                .addClass('variation-text-selected');

            $(this).addClass('selected');

            // trigger the event (add to cart)
            let term_taxonomy = $(this).find('.info').attr('attribute-term-taxonomy');
            let term_value = $(this).find('.info').attr('attribute-term-value');
            $('#' + term_taxonomy).val(term_value).trigger('change');
        }
    });

    $(document).on('click' , '.single-product .quantity .word-plus, .woocommerce-cart.woocommerce-page .quantity .word-plus', function() {
        let new_value = parseInt($(this).parents('.quantity').find('input[type="number"]').val()) + 1;
        let max_value = parseInt($(this).parents('.quantity').find('input[type="number"]').attr('max'));

        if (isNaN(max_value)) {
            max_value = 9999999;
        }
        if (max_value >= new_value) {
            $(this).parents('.quantity').find('input[type="number"]').val(new_value);
        } else {
            $(this).parents('.quantity').find('input[type="number"]').val(max_value);
        }
        $(this).parents('.quantity').find('input[type="number"]').change();
    });

    $(document).on('click' , '.single-product .quantity .word-sub, .woocommerce-cart.woocommerce-page .quantity .word-sub' , function() {
        let new_value = parseInt($(this).parents('.quantity').find('input[type="number"]').val()) - 1;
        let min_value = parseInt($(this).parents('.quantity').find('input[type="number"]').attr('min'));
        if (min_value <= new_value) {
            $(this).parents('.quantity').find('input[type="number"]').val(new_value);
        } else {
            $(this).parents('.quantity').find('input[type="number"]').val(min_value);
        }
        $(this).parents('.quantity').find('input[type="number"]').change();
    });

    $.each($('.single-product .tab-pd-content'), function (key ,value){
       if ($(value).height() > 120) {
           $(value).css('height', '120px');
           $(value).css('overflow', 'hidden');
           $(value).parent('.tab-pd-wrapper').append('<br><button class="tab-read-more more">查看更多</button><br>');
       }
    });

    $('body').on('click', '.tab-read-more', function () {
        if($(this).hasClass('more')) {
            $(this).parent('.tab-pd-wrapper').css('height', '100%');
            $(this).parent('.tab-pd-wrapper').find('.tab-pd-content').first().css('height', '100%');
            $(this).removeClass('more');
            $(this).addClass('short');
            $(this).text('查看更少');
        } else {
            $(this).parent('.tab-pd-wrapper').css('height', '120px');
            $(this).parent('.tab-pd-wrapper').find('.tab-pd-content').first().css('height', '120px');
            $(this).removeClass('short');
            $(this).addClass('more');
            $(this).text('查看更多');
        }
    });


    $('.beta.site-title [rel="home"]').html("");
    $(document).on('click', '#masthead div.site-title', function () {
        location.href = $(this).find("a").attr("href");
    })

    if ($('body.logged-in').length) {
        $('#menu-item-52 > a').text("");
    }

    window.onload = function () {
        if ($('.single-product').length && window.location.search == '') {
            $('.reset_variations').click();
            $('.quantity input[name="quantity"]').val(1);
        }

        $('.single-product .tabs.wc-tabs').remove();
        $('.single-product .woocommerce-Tabs-panel.panel.entry-content.wc-tab').css('display', 'block');
        $('.single-product .woocommerce-Tabs-panel.panel.entry-content.wc-tab').css('width', '100%');

        if ($('.single-product .woocommerce-Tabs-panel.woocommerce-Tabs-panel--reviews.panel.entry-content.wc-tab').length &&
            $('.single-product section.up-sells.upsells.products').length
        ) {
            let tmpReviewHtml = '<br><br><br><div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--reviews panel entry-content wc-tab" id="tab-reviews" role="tabpanel" aria-labelledby="tab-title-reviews" style="display: block; width: 100%;">'
                + $('.single-product .woocommerce-Tabs-panel.woocommerce-Tabs-panel--reviews.panel.entry-content.wc-tab').html()
                + '</div><br><br>';
            $('.single-product .woocommerce-Tabs-panel.woocommerce-Tabs-panel--reviews.panel.entry-content.wc-tab').remove();
            $(tmpReviewHtml).insertBefore($('.single-product section.related.products'));
        }
    }

    $('body form.woocommerce-product-search').attr('action', location.origin + location.pathname);
    $('body form.woocommerce-product-search input[name="post_type"]').remove();
    $('body form.search-form').remove();
})
