'use strict';

angular.module('biffyApp').controller(
    'ChecklistDeviceTypeRepairsEditController',
    function($q, $scope, $stateParams, $state, RestangularAppService, NotifierService)
    {
        $scope.id = $stateParams.id || null;
        $scope.data = {};
        $scope.mode = 'Loading';

        $scope.isAdd = function()
        {
            return $scope.id === null;
        };

        $scope.isEdit = function()
        {
            return $scope.mode === 'Edit';
        };

        if($scope.isAdd())
        {
            $scope.mode = 'Add';
            $scope.data.device_type_id = $stateParams.deviceTypeId;
        }
        else
        {
            RestangularAppService.one('devicerepairs', $scope.id).get().then(
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
        }

        $scope.store = function()
        {
            RestangularAppService.all('devicerepairs').post($scope.data).then(
                function()
                {
                    $state.transitionTo('checklists.devicetypes.repairs');
                },
                function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.update = function()
        {
            $scope.data.put().then(
                function()
                {
                    $state.transitionTo('checklists.devicetypes.repairs');
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
                    $state.transitionTo('checklists.devicetypes.repairs');
                },
                function(data)
                {
                    NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.cancel = function()
        {
            $state.transitionTo('checklists.devicetypes.repairs');
        };
    }
);