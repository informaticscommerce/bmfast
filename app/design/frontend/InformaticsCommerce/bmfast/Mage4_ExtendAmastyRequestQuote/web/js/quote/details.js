define([
    'jquery',
    'ko',
    'underscore',
    'uiComponent',
    'mage/storage',
    'Magento_Checkout/js/model/url-builder',
    'Magento_Checkout/js/model/totals',
    'Magento_Checkout/js/checkout-data'
], function ($, ko, _, Component, storage, urlBuilder, totalsService, checkoutData) {
    'use strict';

    return Component.extend({
        remark: ko.observable(window.checkoutConfig.quoteData['remarks']),
        checkDelay: 2000,
        remarkCheckTimeout: 0,
        isLoading: ko.observable(false),
        attributeRenderer: [],
        attributes: [],

        initObservable: function () {
            var self = this;
            this.remark.subscribe(function (value) {
                clearTimeout(self.remarkCheckTimeout);
                self.isLoading(true);

                self.remarkCheckTimeout = setTimeout(function () {
                    storage.put(
                        self._getUrl(),
                        JSON.stringify({'remark': value}),
                        false
                    ).always(function () {
                        self.isLoading(false);
                    });
                }, self.checkDelay);
            });
            if (this.notLoggedIn()) {
                $(this.getAttributes()).each(function (index, attribute) {
                    this.initObservableAttribute(attribute);
                }.bind(this));
            }
            this._super();
            return this;
        },

        initObservableAttribute: function (attribute) {
            var defaultValue = this.getDefaultValue(attribute.code, attribute.defaultValue);
            switch (attribute.type) {
                case 'select':
                case 'boolean':
                    defaultValue = parseInt(defaultValue);
                    break;
            }
            attribute.value = ko.observable(defaultValue);
            attribute.value.subscribe(function (value) {
                var shippingData = this.getShippingData();
                shippingData.amasty_quote[attribute.code] = value;
                checkoutData.setShippingAddressFromData(shippingData);
            }.bind(this));
        },

        getShippingData: function () {
            var shippingData = checkoutData.getShippingAddressFromData();
            if (shippingData === null) {
                shippingData = {
                    amasty_quote: {}
                };
            } else if (!shippingData.amasty_quote) {
                shippingData.amasty_quote = {};
            }

            return shippingData;
        },

        getDefaultValue: function (key, initialValue) {
            var data = checkoutData.getShippingAddressFromData();

            return data && data.amasty_quote && typeof data.amasty_quote[key] != 'undefined'
                ? data.amasty_quote[key]
                : initialValue;
        },

        _getUrl: function () {
            return urlBuilder.createUrl('/amasty_quote/updateRemark', {});
        },

        notLoggedIn: function () {
            return !window.checkoutConfig.isCustomerLoggedIn;
        },

        getAttributes: function () {
            return this.attributes;
        },

        getAttributeRenderer: function (type) {
            return this.attributeRenderer[type] || 'textRenderer';
        }
    });
});
