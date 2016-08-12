'use strict';

angular.module('biffyApp').directive(
    'workOrderStatusChanger',
    function (RestangularAppService)
    {
        function link(scope)
        {
            scope.workOrderStatuses = [
                '', 'Awaiting Repair', 'Repair in Progress', 'Awaiting Callback', 'Awaiting Parts', 'Unrepairable - RFP',
                'Repaired - RFP', 'Need To Order Parts', 'Picked Up', 'Awaiting Device', 'Device Abandoned',
                'Awaiting Approval', 'Declined - RFP', 'Sale Completed', 'Quote', 'Approved'
            ];

            scope.workOrderStatusRange = function()
            {
                return scope.workOrderStatuses.slice(1, scope.workOrderStatuses.length);
            };

            scope.setWorkOrderStatusId = function(workOrderStatusId)
            {
                scope.currentWorkOrder.workorder_status_id = workOrderStatusId;

                return RestangularAppService.one('workorders', scope.currentWorkOrder.id).put({workorder_status_id : workOrderStatusId}).then(
                    function (response)
                    {
                        return response;
                    }
                );
            };
        }

        return {
            restrict: 'E',
            link : link,
            templateUrl : 'src/directives/workorder.status.changer.directive.template.html'
        };
    }
);