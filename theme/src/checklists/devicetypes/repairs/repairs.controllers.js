'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'ChecklistDeviceTypeRepairsController',
    function($rootScope, $state, $scope, $location, $filter, ngTableParams, RestangularAppService)
    {
        $scope.id = $state.params.deviceTypeId;

        $scope.$on(
            'onModalUpdate',
            function(event, args)
            {
                $scope.tableParams.reload();
            }
        );

        $scope.tableParams = new ngTableParams(
            angular.extend(
                {
                    page : 1,
                    count : 10,
                    sorting:
                    {
                        name : 'asc'
                    }
                },
                $location.search()
            ),
            {
                total: 0,
                getData: function($defer)
                {
                    RestangularAppService.all('devicerepairs?filter[device_type_id]=' + $scope.id).getList().then(
                        function(result)
                        {
                            $defer.resolve(result);
                        },
                        function()
                        {

                        }
                    );
                }
            }
        );
    }
);

