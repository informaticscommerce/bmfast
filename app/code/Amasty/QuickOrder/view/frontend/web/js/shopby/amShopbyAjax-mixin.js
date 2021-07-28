define([
    'jquery',
    'uiRegistry',
    'ko'
], function ($, registry, ko) {
    'use strict';

    return function (widget) {
        $.widget('mage.amShopbyAjax', widget, {
            quickOrderGridSelector: '[data-amqorder-js="grid"]',

            reloadHtml: function (data) {
                this._super(data);

                if ($(this.quickOrderGridSelector).length && registry.get('grid')) {
                    registry.remove('grid');
                    ko.applyBindingsToNode($(this.quickOrderGridSelector)[0]);
                }
            }
        });

        return $.mage.amShopbyAjax;
    }
});
