'use strict';

angular.module('biffyApp').controller(
    'ChecklistFunctionsDeviceTypesEditController',
    function($q, $scope, $stateParams, $state, RestangularAppService, NotifierService)
    {
        $scope.itemList = RestangularAppService.all('items?all=1').getList().$object;

        $scope.selectItemSuggestion = function(id)
        {
            $scope.data.item_id = id;

            RestangularAppService.one('items', id).get().then(
                function(result)
                {
                    $scope.data.item = result.plain();
                },
                function()
                {

                }
            )
        };

        RestangularAppService.all('devicetypes?all=1').getList().then(
            function(result)
            {
                $scope.deviceTypes = result.plain();
                $scope.deviceTypeOptions = $scope.deviceTypes;

                RestangularAppService.all('checklistfunctions').getList().then(
                    function(result)
                    {
                        $scope.checklistFunctions = result.plain();

                        if($scope.isAdd())
                        {
                            $scope.mode = 'Add';
                        }
                        else
                        {
                            RestangularAppService.one('devicechecklists', $scope.id).get().then(
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
        $scope.data = { device_type_id : '1', checklist_function_id : '1', item_id : '1' };
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
            RestangularAppService.all('devicechecklists').post($scope.data).then(
                function()
                {
                    $state.transitionTo('checklists.functions-devicetypes');
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
                    $state.transitionTo('checklists.functions-devicetypes');
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
                    $state.transitionTo('checklists.functions-devicetypes');
                },
                function(data)
                {
                    NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.cancel = function()
        {
            $state.transitionTo('checklists.functions-devicetypes');
        };
    }
);