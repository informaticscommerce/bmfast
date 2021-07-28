var config = {
    config: {
        mixins: {
            'Magento_Catalog/js/catalog-add-to-cart': {
                'Amasty_RequestQuote/js/product/catalog-add-to-cart': true
            }
        }
    },
    shim: {
        'Magento_Checkout/js/view/shipping': {
            deps: ['Amasty_RequestQuote/js/actions/shipping/add-address']
        },
        'Magento_Checkout/js/view/shipping-address/list': {
            deps: ['Amasty_RequestQuote/js/actions/shipping/add-address']
        }
    }
};
