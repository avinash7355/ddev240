/**
 * Pgrid Multiselect Element
 */

define([
    'underscore',
    'Magento_Ui/js/form/element/multiselect'
], function (_, Multiselect) {
    'use strict';

    return Multiselect.extend({
        defaults: {
            defaultValue: null,
            isInitDefaultVal: false
        },

        setInitialValue: function () {
            if (_.isUndefined(this.value()) && !_.isNull(this.defaultValue)) {
                this.value(this.normalizeData(String(this.defaultValue)));
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
