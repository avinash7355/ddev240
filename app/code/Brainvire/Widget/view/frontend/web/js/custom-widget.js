define([
    'jquery',
    'mage/template',
    'Magento_Ui/js/modal/alert',
    'jquery/ui'
], function ($) {
    'use strict';

    $.widget('custom.enabledTasks', {
        options: {
            taskListSelector: '#task-table input[type="checkbox"]',
            enabledTasksSelector: '#enabled-tasks-list',
            submitButtonSelector: '#update-status-button',
            updateStatusUrl: '/widget/index/updateStatus'
        },

        _create: function () {
            console.log("adfas")
            this._bindEvents();
        },

        _bindEvents: function () {
            var self = this;
            var taskList = $(this.options.taskListSelector);
            var enabledTasksList = $(this.options.enabledTasksSelector);

            // Handle checkbox change event
            taskList.on('change', function () {
                self._updateEnabledTasksList(taskList, enabledTasksList);
            });

            // Handle button click event (if needed)

            $(this.options.submitButtonSelector).on('click', function () {
                // alert("Heloo");
                self._updateStatus();
            });

            // Initial update of enabled tasks list
            this._updateEnabledTasksList(taskList, enabledTasksList);
        },

        _updateEnabledTasksList: function (taskList, enabledTasksList) {
            var enabledTasks = [];
            taskList.each(function () {
                if ($(this).is(':checked')) {
                    var taskId = $(this).data('task-title');
                    enabledTasks.push(taskId);
                }
            });

            enabledTasksList.text('Enabled: ' + enabledTasks.join(', '));
        },
        _updateStatus: function () {
            var self = this;
            var taskData = [];

            $(this.options.taskListSelector).each(function () {
                var taskId = $(this).data('task-id');
                var status = $(this).is(':checked') ? 1 : 0;

                taskData.push({
                    entity_id: taskId,
                    status: status
                });
            });

            $.ajax({
                url: self.options.updateStatusUrl,
                type: 'POST',
                data: {
                    tasks: taskData
                },
                success: function (response) {
                    if (response.success) {
                        alert('Data updated successfully');
                    } else {
                        alert('update failed');
                    }
                },
                error: function () {
                    alert('Error updating status. Please try again.');
                }
            });
        }
            
});


    return $.custom.enabledTasks;
    
});




