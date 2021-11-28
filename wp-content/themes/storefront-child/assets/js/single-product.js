jQuery(function($){
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

        $.each($('body.single-product li.product'), function (key ,value) {
            if ($(value).find('a span.price span.woocommerce-Price-amount.amount').length == 1) {
                $(value).find('a span.price').css('line-height', 3.5);

            } else {
                $(value).find('a span.price').css('line-height', 1.3);
            }
        })

    }
});
