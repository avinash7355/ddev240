define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/quote'
    ],
    function (Component, quote) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Brainvire_Paymentadd/customaddfield'
            },
            initObservable: function () {
                this._super()
                    .observe('custom_field');
                    console.log("customadd");    
                return this;
            },
            /**
             * Send value to the quote
             */
            getCustomFieldValue: function () {
                return this.custom_field();
            },
            /**
             * Send value to the quote
             */
            setCustomFieldValue: function (value) {
                this.custom_field(value);
            },
        });
    }
);