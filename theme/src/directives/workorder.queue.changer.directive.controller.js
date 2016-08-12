'use strict';

angular.module('biffyApp').directive(
    'workOrderQueueChanger',
    function (RestangularAppService)
    {
        function link(scope)
        {
            scope.isSingleDayQueue = function()
            {
                if (scope.currentWorkOrder == null)
                {
                    return true;
                }

                switch (parseInt(scope.currentWorkOrder.queue))
                {
                    case 0:
                        var currentTime = new Date(scope.currentWorkOrder.created_at);
                        currentTime.setHours(0, 0, 0, 0);

                        var updateTime = new Date(scope.currentWorkOrder.next_update);
                        updateTime.setHours(0, 0, 0, 0);

                        return currentTime <= updateTime && currentTime >= updateTime;
                    case 1:
                        return true;
                    case 2:
                        return false;
                }
            };

            scope.changeWorkOrderQueue = function (val)
            {
                scope.currentWorkOrder.queue = val;
                return RestangularAppService.one('workorders', scope.currentWorkOrder.id).put({queue : val});

            };
        }

        return {
            restrict: 'E',
            link : link,
            templateUrl : 'src/directives/workorder.queue.changer.directive.template.html'
        };
    }
);