define([
    'jquery',
    'underscore',
    'Magento_Ui/js/grid/data-storage'
], function ($, _, Storage) {
    'use strict';

    return Storage.extend({

        /**
         * Add categories data from request to data object.
         *
         * @param {Object} request - request object.
         * @returns {jQueryPromise}
         */
        getRequestData: function (request) {
            var defer = $.Deferred(),
                resolve = defer.resolve.bind(defer),
                delay = this.cachedRequestDelay,
                result;

            result = {
                items: this.getByIds(request.ids),
                totalRecords: request.totalRecords,
                errorMessage: request.errorMessage
            };

            if (this.isProductListing() && request.categories) {
                result.categories = request.categories;
            }

            delay ?
                _.delay(resolve, delay, result) :
                resolve(result);

            return defer.promise();
        },

        /**
         * Caches requests object with product categories data.
         *
         * @param {Object} data - data associated with request.
         * @param {Object} params - request parameters.
         * @returns {Object} - dataStorage object.
         */
        cacheRequest: function (data, params) {
            var result = this._super();

            if (this.isProductListing() && data.categories) {
                this.getRequest(params).categories = data.categories;
            }

            return result;
        },

        /**
         * @returns {boolean}
         */
        isProductListing: function () {
            return this.namespace === 'product_listing.product_listing_data_source';
        },
    });
});
