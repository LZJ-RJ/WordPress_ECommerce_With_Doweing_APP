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
            $(this).parent('.single-pd-spec-option').find('.box')
                .removeClass('selected');

            // normal
            $(this).parent('.single-pd-spec-option').find('.box')
                .removeClass('variation-box-hover variation-box-clicked')
                .addClass('variation-box-normal');

            $(this).parent('.single-pd-spec-option').find('.box .info')
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

    $('.single-product .quantity .word-plus').on('click', function (){
        let new_value = parseInt($(this).parents('.quantity').find('input[name="quantity"]').val()) + 1;
        let max_value = parseInt($(this).parents('.quantity').find('input[name="quantity"]').attr('max'));
        if (max_value >= new_value) {
            $(this).parents('.quantity').find('input[name="quantity"]').val(new_value);
        } else {
            $(this).parents('.quantity').find('input[name="quantity"]').val(max_value);
        }
    });

    $('.single-product .quantity .word-sub').on('click', function (){
        let new_value = parseInt($(this).parents('.quantity').find('input[name="quantity"]').val()) - 1;
        let min_value = parseInt($(this).parents('.quantity').find('input[name="quantity"]').attr('min'));
        if (min_value <= new_value) {
            $(this).parents('.quantity').find('input[name="quantity"]').val(new_value);
        } else {
            $(this).parents('.quantity').find('input[name="quantity"]').val(min_value);
        }
    });

    window.onload = function () {
        $('.single-product .tabs.wc-tabs').remove();
        $('.single-product .woocommerce-Tabs-panel.panel.entry-content.wc-tab').css('display', 'block');
        $('.single-product .woocommerce-Tabs-panel.panel.entry-content.wc-tab').css('width', '100%');
    }
})
