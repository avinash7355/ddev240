define([
    'ko',
    'uiComponent',
    'uiLayout',
    'mageUtils'
], function (ko, Component, layout, utils) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Amasty_Pgrid/ui/grid/modal/tierprice/row',
            websites: [],
            groups: [],
            priceTypes: [],
            htmlClass: '',
            htmlName: '',
            priceValueValidationClass: '',
            elemIndex: 0,
            rowsConfig: {
                component: 'Magento_Ui/js/form/element/abstract',
                selectedPriceType: ko.observable('fixed'),
                template: 'Amasty_Pgrid/ui/grid/modal/tierprice/row'
            },
            modules: {
                columnTierPrices: "index = amasty_tier_price"
            }
        },

        initialize: function () {
            this._super();

            this.reloadTierPrices();

            return this;
        },

        deleteRow: function (element) {
            this.removeChild(element);
        },

        /**
         * @param {Object} rowData
         * @returns void
         */
        addRow: function (rowData) {
            const row = this.createRow(this.elemIndex, rowData);

            layout([ row ]);
            this.insertChild(row.name);
            this.elemIndex += 1;
        },

        /**
         * @param {Number} index
         * @param {Object} rowData
         * @returns {*}
         */
        createRow: function (index, rowData) {
            return utils.extend(this.rowsConfig, {
                'websites': this.websites,
                'selectedWebsite': rowData?.website_id || '',
                'groups': this.groups,
                'selectedGroup': rowData?.cust_group || '',
                'htmlClass': this.htmlClass,
                'htmlName': this.htmlName,
                'name': 'price-row-' + index,
                'priceValueValidationClass': this.priceValueValidationClass,
                'priceTypes': this.priceTypes,
                'selectedPriceType': ko.observable(rowData?.value_type || ''),
                'qty': rowData?.price_qty || '',
                'itemPrice': rowData?.price || ''
            });
        },

        getWebsite: function (name, currency) {
            currency = currency ? '[' + currency + ']' : '';

            return name + ' ' + currency;
        },

        reloadTierPrices: function () {
            let columnComponent = this.columnTierPrices(),
                rows = columnComponent?.rowData?.amasty_tier_price_modal;

            if (!rows?.length) {
                return;
            }

            rows.forEach(row => this.addRow(row));
        }
    });
});
