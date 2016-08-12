'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'ChecklistApprovalsController',
    function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService)
    {
        $scope.approveDevice = function(device)
        {
            RestangularAppService.one('deviceapprovals', device.id).put({approved:1}).then(
                function()
                {
                    $scope.tableParams.reload();
                },
                function()
                {

                }
            )
        };

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
                    RestangularAppService.all('deviceapprovals').getList().then(
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
