'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'EditDeviceTypeRepairItemModalController',
    function(deviceRepairId, $scope, $modalInstance, RestangularAppService, NotifierService)
    {
        $scope.deviceRepairId = deviceRepairId;

        RestangularAppService.all('devicerepairoptions').getList().then(
            function(result)
            {
                $scope.deviceRepairOptions = result;
            },
            function()
            {
                NotifierService.error('Could not load ' + JSON.stringify(data.data.messages));
            }
        );

        $scope.data = {
            device_repair_id : deviceRepairId,
            device_repair_option_id : 1
        };

        $scope.cancel = function()
        {
            $scope.close('cancel');
        };

        $scope.close = function(reason)
        {
            $modalInstance.dismiss(reason);
        };

        $scope.save = function()
        {
            RestangularAppService.one('devicerepairs', $scope.deviceRepairId).all('items').post($scope.data).then(
                function()
                {
                    $scope.close('success');
                },
                function(data)
                {
                    NotifierService.error('Could not save ' + JSON.stringify(data.data.messages));
                }
            );
        };
    }
);
