/**
 * Pgrid Select Element
 */

define([
    'underscore',
    'Magento_Ui/js/form/element/select'
], function (_, Select) {
    'use strict';

    return Select.extend({
        defaults: {
            defaultValue: null,
            isInitDefaultVal: false,
            listens: {
                value: 'checkValue'
            }
        },

        checkValue: function (value) {
            if (this.initialValue !== value) {
                window.isPgridEditable = true;
            }
        },

        setInitialValue: function () {
            if (_.isUndefined(this.value()) && !_.isNull(this.defaultValue)) {
                this.value(this.normalizeData(this.defaultValue));
                this.isInitDefaultVal = true;
            }

            return this._super();
        },

        hasChanged: function () {
            if (this._super()) {
                return true;
            }

            return this.isInitDefaultVal;
        }
    });
});
