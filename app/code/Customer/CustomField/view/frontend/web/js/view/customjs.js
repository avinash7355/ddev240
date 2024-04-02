define([
    'jquery',
    'ko',
    'uiComponent'], function ($, ko, Component) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Customer_CustomField/customtemp'
        },
        initialize: function () {
            console.log("avinash222")
            this._super();
          
            return this;
        },
    });});