'use strict';

angular.module('biffyApp').controller(
    'DeviceTypesRepairsItemsEditController',
    function($rootScope, $state, $scope, $stateParams, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService)
    {
        $scope.id = $stateParams.repairOptionId || null;
        $scope.mode = 'Loading';

        RestangularAppService.one('devicerepairs', $stateParams.deviceRepairId).one('items', $scope.id).get().then(
            function(data)
            {
                $scope.mode = 'Edit';
                $scope.data = data;
            },
            function(data)
            {
                NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
            }
        );

        $scope.update = function()
        {
            $scope.data.put().then(
                function()
                {
                    $state.transitionTo('devicetypes.edit.repairs', {deviceTypeId:$stateParams.deviceTypeId, deviceRepairId:$stateParams.deviceRepairId});
                },
                function(data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.destroy = function()
        {
            $scope.data.remove().then(
                function()
                {
                    $state.transitionTo('devicetypes.edit.repairs', {deviceTypeId:$stateParams.deviceTypeId, deviceRepairId:$stateParams.deviceRepairId});
                },
                function(data)
                {
                    NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.cancel = function()
        {
            $state.transitionTo('devicetypes.edit.repairs', {deviceTypeId:$stateParams.deviceTypeId, deviceRepairId:$stateParams.deviceRepairId});
        };
    }
);