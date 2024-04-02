define([
    'jquery',
    'ko',
    'uiComponent'
], function ($, ko, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Brainvire_CustomComponent/knockouttest',
            customComponentData: []
        },

        initialize: function () {
            this._super();
        },
        customComponentData: ko.observableArray([]),

        updateData: function (data) {

            var self = this;
            var updatedData = [];
            $('input[type="checkbox"]').each(function () {
                var taskId = $(this).attr('data-task-id');
                var status = $(this).is(':checked')?1 : 0;
                updatedData.push({entity_id : taskId, status: status});
            })
            // console.log(updatedData);

            // console.log(updatedData);
            $.ajax({
                url: '/compo/index/updateDataController', // Replace with your controller route
                type: 'POST',
                data: {tasks: updatedData},
                dataType: 'json',
                success: function (response) {
                    // console.log(updatedData);
                    if (response.success) { 
                        // Update your UI or customComponentData array if needed
                        alert("Data updated");
                    } else {
                        // Handle error
                        console.error(response.message);
                    }
                },
                error: function () {
                    // Handle AJAX error
                    alert("Data saved");
                }
            });
        }
    });
});