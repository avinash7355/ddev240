/**
 * Pgrid Thumbnail Component
 */
define([
    'jquery',
    'Magento_Ui/js/grid/columns/column',
    'Amasty_Pgrid/js/model/column',
    'Magento_Ui/js/modal/modal',
    'underscore',
    'Amasty_Pgrid/js/action/messages',
    'mage/translate',
    'Amasty_Pgrid/js/action/ajax',
    'Magento_Ui/js/lib/validation/utils',
    'mage/validation',
    'prototype'
], function ($, Column, amColumn, modal, _, amMessage, $t, actionAjax, utils) {
    'use strict';

    delete amColumn['defaults'];

    var column = _.extend({
        defaults: {
            modalUrl: '',
            saveUrl: '',
            modalComponent: '',
            defaultStoreId: 0,
            selectors: {
                formKey: '[name="form_key"]',
                tierPricesForm: '#tier-prices-form',
                tierPriceField: '[data-ampgrid-js="tierprice"] select, [data-ampgrid-js="tierprice"] input'
            },
            cssClasses: {
                modal: 'ampgrid-modal-prices',
                primary: 'action-primary'
            },
            text: {
                linkDetail: $t('Go to Details Page'),
                upload: $t('Edit Tier Price for '),
                save: $t('Save')
            },
            imports: {
                namespace: '${ $.parentName }:ns'
            },
            modules: {
                parent: '${ $.parentName }',
                source: '${ $.provider }',
                editor: '${ $.parentName }_amasty_editor',
                modalTierPrices: 'tier-prices'
            }
        },

        initialize: function () {
            this._super();

            $.validator.addMethod('validate-positive-percent-decimal', function (value) {
                var numValue;

                if (utils.isEmptyNoTrim(value) || !/^\s*-?\d*(\.\d*)?\s*$/.test(value)) {
                    return false;
                }

                numValue = utils.parseNumber(value);

                if (isNaN(numValue)) {
                    return false;
                }

                return utils.isBetween(numValue, 0.01, 100);
            }, 'Please enter a valid percentage discount value greater than 0.');

            return this;
        },

        /**
         * After click on tier price
         *
         * @param {object} row - row data
         */
        fieldHandler: function (row) {
            if (!this.ampgrid_editable()) {
                return this._super();
            }

            this.rowData = row;
            this.productId = row.entity_id;
            this.storeId = row.store_id || this.defaultStoreId;

            return this.getEditModalHtml(row);
        },

        /**
         * Get tier prices modal html
         *
         * @param {object} row - row data
         */
        getEditModalHtml: function (row) {
            var data = {
                entity_id: row.entity_id,
                store_id: row.store_id
            };

            actionAjax(
                'GET',
                this.modalUrl,
                data,
                this.showEditTierPriceModal.bind(this, row.name),
                this.editor().onSaveError
            );
        },

        /**
         * Create edit tier price modal
         *
         * @param {string} name - product name
         * @param {string} modalHtml - modal html
         */
        showEditTierPriceModal: function (name, modalHtml) {
            var self = this;

            this.previewPopup = $('<div></div>').html(modalHtml);

            this.previewPopup.modal({
                title: this.text.upload + '"' + name + '"',
                innerScroll: true,
                type: 'slide',
                modalClass: this.cssClasses.modal,
                buttons: [ {
                    text: this.text.save,
                    class: this.cssClasses.primary,
                    click: function () {
                        self.actionSave();
                    }
                } ],
                closed: function () {
                    this.closest('.ampgrid-modal-prices').remove();
                }
            });

            this.modalTierPrices()?.destroy();
            this.previewPopup.modal('openModal');

            $(this.selectors.tierPricesForm).validation({errorElement: 'label'});
        },

        /**
         * Prepare data for saving
         */
        actionSave: function () {
            var formData = new FormData(),
                name = 'amastyItems[' + this.productId + ']',
                fields = $(this.selectors.tierPriceField),
                data = this.source().get('params');

            if (!$(this.selectors.tierPricesForm).valid()) {
                return;
            }

            this.getParamsToFormData(data, formData);

            formData.append('form_key', $(this.selectors.formKey).val());
            formData.append('store_id', this.storeId);
            formData.append('namespace', this.namespace);

            // empty string is set to amastyItems param to delete existed tier prices.
            if (fields.length === 0) {
                formData.append('amastyItems[' + this.productId + '][tier_price]', '');
            } else {
                _.each(fields, function (field) {
                    formData.append(field.name.replace('amastyItems', name), field.value);
                });
            }

            this.saveTierPrices(formData);
        },

        /**
         * Prepare source params
         *
         * @param {object} data - params
         * @param {object} formData - formData
         * @param {string} recKey - key prefix
         */
        getParamsToFormData: function (data, formData, recKey) {
            _.each(data, function (value, key) {
                key = recKey ? recKey + '[' + key + ']' : key;

                if (_.isObject(value)) {
                    return this.getParamsToFormData(value, formData, key);
                }

                formData.append(key, value);
            }.bind(this));
        },

        /**
         * After save success callback
         *
         * @param {string} response - modal html
         */
        afterSaveSuccess: function (response) {
            this.previewPopup.modal('closeModal');
            this.editor().onDataSaved(false, response);
        },

        /**
         * After save error callback
         *
         * @param {string} errorThrown - text error
         */
        afterSaveError: function (errorThrown) {
            this.previewPopup.modal('closeModal');
            this.editor().onSaveError(errorThrown);
        },

        /**
         * Save method call
         *
         * @param {object} formData - formData
         */
        saveTierPrices: function (formData) {
            actionAjax(
                'POST',
                this.saveUrl,
                formData,
                this.afterSaveSuccess.bind(this),
                this.afterSaveError.bind(this),
                { 'Accept': 'application/json' }
            );
        },

        getFieldHandler: function (row) {
            return this.fieldHandler.bind(this, row);
        }
    }, amColumn);

    return Column.extend(column);
});
