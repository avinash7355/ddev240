define(
    [
    'Customer_ExtraField/js/view/checkout/summary/fee'
    ],
    function (Component) {
    'use strict';
    return Component.extend({
    /**
    * @override
    */
    isDisplayed: function () {
        console.log("Display working");
    return true;
    }
    });
    }
    );