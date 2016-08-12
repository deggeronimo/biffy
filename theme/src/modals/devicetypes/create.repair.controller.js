'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'EditDeviceTypeRepairModalController',
    function(deviceTypeId,  $scope, $modalInstance, RestangularAppService, NotifierService)
    {
        $scope.deviceRepairTypes = RestangularAppService.all('devicerepairtypes').getList({all: 1}).$object;

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
            console.log($scope.selectedDeviceRepairTypes);

            var createdCount = 0;
            var templateCount = $scope.selectedDeviceRepairTypes.length;

            for (var i=0; i<$scope.selectedDeviceRepairTypes.length; i++)
            {
                var deviceRepairType = $scope.selectedDeviceRepairTypes[i];

                var data = {
                    device_type_id : deviceTypeId,
                    device_repair_type_id : deviceRepairType.id
                };

                RestangularAppService.all('devicerepairs').post(data).then(
                    function()
                    {
                        createdCount ++;

                        if (createdCount == templateCount)
                        {
                            $scope.close('success');
                        }
                    },
                    function(data)
                    {
                        NotifierService.error('Could not save ' + JSON.stringify(data.data.messages));
                    }
                );
            }
        };
    }
);

/*
 var modalInstance = $modal.open(
 {
 templateUrl : 'src/modals/createdevice.modal.html',
 controller : 'EditDeviceModalController',
 size : size,
 resolve : {
 customer_id : function()
 {
 return $scope.currentCustomer.id;
 },
 device_id : function()
 {
 return 'new';
 }
 }
 });
 */
