/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/quote'
], function (Component, quote) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magento_Checkout/summary/subtotal'
        },

        /**
         * Get pure value.
         *
         * @return {*}
         */
        getPureValue: function () {
            var totals = quote.getTotals()();
            //console.log(totals);
            if (totals) {
                return totals.base_grand_total;
            }

            return quote.base_grand_total;
        },

        getPureValueTax: function () {
            var totals = quote.getTotals()();

            if (totals) {

                return totals.base_tax_amount;
            }

            return quote.base_tax_amount;
        },

        /**
         * @return {*|String}
         */
        getValue: function () {
            return this.getFormattedPrice(this.getPureValue());
        },

        getTax: function () {
            return this.getFormattedPrice(this.getPureValueTax());
        }

    });
});
