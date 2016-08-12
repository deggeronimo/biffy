'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'WorkOrderEditController',
    function($rootScope, $state, $scope, $global, $q, $log, $location, $filter, ngTableParams, RestangularAppService, NotifierService, $modal)
    {
        $global.set('setMainBG', true);

        $scope.workOrderStatusRange = function()
        {
            return scope.workOrderStatuses.slice(2, scope.workOrderStatuses.length);
        };

        $scope.ratingDescriptions = [
            '',
            'Very Poor. Device severely, physically, and/or cosmetically damaged.  Device broken apart, pieces missing, known liquid damage, scuffs, scratches, dents all over etc.',
            'Poor. Physically damaged components (cracked glass/lcd for instance), known liquid damage and minor to severe cosmetic wear.  Device still in one piece.',
            'Good. Minor amounts of physical damage visible (cracked glass for instance). Does not include known liquid damage. Regular use cosmetic wear (minor scratches, scuffs, dents in more than 5 areas)',
            'Very good. No visible physical damage. Little to almost no cosmetic wear. (No more than 3-5 small, minor cosmetic blemishes.)',
            'Like New. No visible physical damage or cosmetic wear.'
        ];

        $scope.workorderLoaded = false;
        $scope.currentWorkOrder = null;
        $scope.currentWorkOrderId = $state.params.workOrderId;

        $scope.reload = function()
        {
            $scope.loadWorkOrder();
        };

        $scope.loadWorkOrder = function()
        {
            if ($scope.currentWorkOrderId.length == 0)
            {
                NotifierService.error('Please load this page from the Point of Sale Customer Information page.');
                return;
            }

            $q.all([
                RestangularAppService.one('workorders', $scope.currentWorkOrderId).get(),
                RestangularAppService.all('workorder-statuses').getList({all: 1, 'filter[user_can_set]': 1})
            ]).then(function(result) {
                    $scope.workOrderStatuses = result[1];
                    result = result[0];
                    $scope.currentWorkOrder = result.plain();
                    $scope.workorderLoaded = true;
                    result.workorder_status_id = parseInt(result.workorder_status_id);
                    $scope.selectedWorkOrderStatusId = result.workorder_status_id;
                    $scope.diagnostics = JSON.parse($scope.currentWorkOrder.quickdiag);
                    $scope.itemswithdevice = JSON.parse($scope.currentWorkOrder.itemswithdevice);

                    $scope.currentWorkOrder.isDirty = false;
                }
            );
        };

        $scope.loadWorkOrder();

        $scope.workOrderNote = {};

        $scope.createWorkOrderNote = function () {
            RestangularAppService.one('workorders', $scope.currentWorkOrderId).all('notes').post($scope.workOrderNote).then(
                function()
                {
                    $scope.loadWorkOrder();
                },
                function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                }
            );
        };

        // todo formatting
        // todo move into dedicated area
        $scope.filterNoteDateTime = function(datetime)
        {
            var currentTime = new Date();
            currentTime.setHours(0, 0, 0, 0);

            var noteTime = new Date(datetime);
            noteTime.setHours(0, 0, 0, 0);

            var returnTime = new Date(datetime);

            if (currentTime <= noteTime && currentTime >= noteTime)
            {
                return 'Today at ' + returnTime.toLocaleTimeString();//returnTime.getHours() + ':' + returnTime.getMinutes() + ':' + returnTime.getSeconds();
            }
            else
            {
                return returnTime.toLocaleDateString() + ' ' + returnTime.toLocaleTimeString();
            }
        };

        $scope.openEditCustomerModal = function(size)
        {
            var modalInstance = $modal.open(
            {
                templateUrl : 'src/modals/editcustomer.modal.html',
                controller : 'EditCustomerModalController',
                size : size,
                resolve : {
                    customerId : function()
                    {
                        return $scope.currentWorkOrder.device.customer_id;
                    }
                }
            });
        };
    })
    .controller('CollapseCtrl', function ($scope) {
        $scope.isCollapsed = false;
    });
