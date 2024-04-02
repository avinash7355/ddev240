define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'achpayment',
                component: 'Brainvire_AchPayment/js/view/payment/method-renderer/achpayment-method'
            }
        );
        return Component.extend({});
    }
);