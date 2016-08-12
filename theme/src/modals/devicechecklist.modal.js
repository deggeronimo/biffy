'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('EditDeviceChecklistModalController',
    function(device_type_id, $scope, $modalInstance, RestangularAppService)
    {
        $scope.init = function(device_type_id) {
            RestangularAppService.all('devicechecklists').getList({'device_type_id':device_type_id}).then(
                function(result)
                {
                    $scope.checklistList = result;

                    for (var i=0; i<$scope.checklistList.length; i++)
                    {
                        $scope.filter($scope.checklistList[i]);
                    }
                },
                function()
                {
                    $scope.cancel();
                }
            );
        };

        $scope.device_type_id = device_type_id;
        $scope.init($scope.device_type_id);

        $scope.filter = function(obj)
        {
            for (var i in obj)
            {
                if (i != 'name' && i != 'checked')
                {
                    console.log('deleting' + i);

                    delete obj[i];
                }
            }
        };

        $scope.cancel = function()
        {
            $modalInstance.dismiss('cancel');
        };

        $scope.save = function()
        {
            var checklistJson = JSON.stringify($scope.checklistList);

            $modalInstance.close(checklistJson);
        };
    }
);