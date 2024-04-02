define([
    'jquery',
    'Amasty_Pgrid/js/action/show-notification'
], function ($, showNotification) {
    'use strict';

    return function (target) {
        return target.extend({
            applyView: function () {
                return showNotification.call(this, this._super);
            },
            initDefaultView: function () {
                var data = this.getViewData(this.defaultIndex);

                if (!_.size(data) && this.current.columns) {
                    this.setViewData(this.defaultIndex, this.current)
                        .saveView(this.defaultIndex);
                    this.defaultDefined = true;
                }

                return this;
            }
        });
    };
});
