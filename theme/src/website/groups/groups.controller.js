'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'WebsiteGroupsController',
    function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService)
    {
        $scope.deleteWebsiteGroup = function(websiteGroup)
        {
            console.log(websiteGroup);
            websiteGroup.remove().then(
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

                    RestangularAppService.all('websitefiltergroups').getList(params.url()).then(
                        function(result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        }, function()
                        {
                            NotifierService.error('Website Filter Groups could not be loaded');
                        }
                    );
                }
            }
        );
    }
);
