'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('EditDeviceModalController',
    function(device_id, customer_id, $scope, $modalInstance, RestangularAppService)
    {
        $scope.init = function(id) {
            RestangularAppService.one('devices', id).get().then(
                function(result)
                {
                    $scope.currentDevice = result;
                },
                function()
                {
                    $scope.cancel();
                }
            );
        };

        RestangularAppService.all('devicechecklists').getList({'selectable':1}).then(
            function(result)
            {
                $scope.deviceTypes = result;
            },
            function()
            {
                $scope.cancel();
            }
        );

        $scope.device_id = device_id;
        if ($scope.device_id == 'new')
        {
            $scope.currentDevice = {
                'name' : 'Device Name',
                'type' : 'Device Type',
                'serial' : ' ',
                'serial_type' : 'Hardware',
                'customer_id' : customer_id
            };
        }
        else
        {
            $scope.init($scope.customer_id);
        }

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
            if ($scope.currentDevice == null)
            {
                NotifierService.error('Cannot save unloaded customer.  Please try again.');
            }
            else
            {
                if ($scope.device_id == 'new')
                {
                    RestangularAppService.all('devices').post($scope.currentDevice).then(
                        function()
                        {
                            $scope.close('success');
                        },
                        function(data)
                        {
                            NotifierService.error('Could not save ' + JSON.stringify(data.data.messages));
                        }
                    );
                }
                else
                {
                    $scope.currentCustomer.put().then(
                        function()
                        {
                            $scope.close('success');
                        },
                        function(data)
                        {
                            NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                        }
                    );
                }
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