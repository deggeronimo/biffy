'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'DeviceTypesFamiliesController',
    function($rootScope, $state, $scope, $location, $filter, ngTableParams, RestangularAppService, NotifierService)
    {
        $scope.isAdd = false;
        $scope.new = {};

        $scope.add = function()
        {
            $scope.isAdd = true;
        };

        $scope.cancel = function(data)
        {
            if (data)
            {
                data.isEdit = false;
            }
            else
            {
                $scope.isAdd = false;
            }
        };

        $scope.store = function()
        {
            RestangularAppService.all('devicefamilies').post($scope.new).then(
                function()
                {
                    $scope.isAdd = false;
                    $scope.tableParams.reload();
                },
                function()
                {

                }
            );
        };

        $scope.update = function(data)
        {
            data.put().then(
                function()
                {
                    data.isEdit = false;
                },
                function()
                {

                }
            )
        };

        $scope.tableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                        given_name: 'asc'
                    }
                },
                $location.search()
            ),
            {
                total: 0,
                getData: function($defer, params)
                {
                    $location.search(params.url());

                    RestangularAppService.all('devicefamilies').getList(params.url()).then(
                        function(result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        }, function()
                        {
                            NotifierService.error('Device Families could not be loaded');
                        }
                    );
                }
            }
        );
    }
);
