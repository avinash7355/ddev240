/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/translate',
    'underscore',
    'Magento_Catalog/js/product/view/product-ids-resolver',
    'Magento_Catalog/js/product/view/product-info-resolver',
    'jquery-ui-modules/widget'
], function ($, $t, _, idsResolver, productInfoResolver) {
    'use strict';

    $.widget('mage.catalogAddToCart', {
        options: {
            
        },

        /** @inheritdoc */
        _create: function () {
            console.log("Widget extension");
        },
    });

    return $.mage.catalogAddToCart;
});