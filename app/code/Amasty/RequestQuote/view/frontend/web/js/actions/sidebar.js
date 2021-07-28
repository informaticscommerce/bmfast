define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'sidebar'
], function ($, customerData, sidebar) {
    $.widget('amasty.quoteSidebar', sidebar, {
        _removeItemAfter: function (elem) {
            var productData = customerData.get('quotecart')().items.find(function (item) {
                return Number(elem.data('cart-item')) === Number(item['item_id']);
            });

            $(document).trigger('ajax:removeFromQuoteCart', productData['product_sku']);
        }
    });

    return $.amasty.quoteSidebar;
});
