'use strict';

angular.module('biffyApp').controller(
    'ChecklistDeviceTypesCarrierController',
    function($q, $scope, $stateParams, $state, RestangularAppService, NotifierService)
    {
        $scope.deviceTypeId = $stateParams.deviceTypeId;
        $scope.selectedDeviceCarriers = [];

        RestangularAppService.all('devicecarriers').getList().then(
            function(result)
            {
                $scope.deviceCarrierList = result.plain();
            },
            function()
            {

            }
        );

        RestangularAppService.one('devicetype', $scope.deviceTypeId).all('carriers').getList().then(
            function(result)
            {
                $scope.selectedDeviceCarriers = result.plain();
            },
            function()
            {

            }
        );

        $scope.update = function()
        {
            RestangularAppService.one('devicetypes', $scope.deviceTypeId).put({carriers:JSON.stringify($scope.selectedDeviceCarriers)}).then(
                function()
                {
                },
                function(data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };
    }
);