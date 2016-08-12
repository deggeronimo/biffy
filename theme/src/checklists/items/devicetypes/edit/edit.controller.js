'use strict';

angular.module('biffyApp').controller(
    'ChecklistItemsDeviceTypesEditController',
    function($q, $scope, $stateParams, $state, RestangularAppService, NotifierService)
    {
        RestangularAppService.all('devicetypes?all=1').getList().then(
            function(result)
            {
                $scope.deviceTypes = result.plain();
                $scope.deviceTypeOptions = $scope.deviceTypes;

                RestangularAppService.all('checklistitems').getList().then(
                    function(result)
                    {
                        $scope.checklistItems = result.plain();

                        if($scope.isAdd())
                        {
                            $scope.mode = 'Add';
                        }
                        else
                        {
                            RestangularAppService.one('deviceitemchecklists', $scope.id).get().then(
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
                    },
                    function()
                    {

                    }
                );

            },
            function()
            {

            }
        );

        $scope.id = $stateParams.id || null;
        $scope.data = { device_type_id : '1', checklist_item_id : '1' };
        $scope.mode = 'Loading';

        $scope.isAdd = function()
        {
            return $scope.id === null;
        };

        $scope.isEdit = function()
        {
            return $scope.mode === 'Edit';
        };

        $scope.store = function()
        {
            RestangularAppService.all('deviceitemchecklists').post($scope.data).then(
                function()
                {
                    $state.transitionTo('checklists.items-devicetypes');
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
                    $state.transitionTo('checklists.items-devicetypes');
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
                    $state.transitionTo('checklists.items-devicetypes');
                },
                function(data)
                {
                    NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.cancel = function()
        {
            $state.transitionTo('checklists.items-devicetypes');
        };
    }
);