'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'WebsiteFiltersController',
    function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService)
    {
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

                    RestangularAppService.all('websitefilters').getList(params.url()).then(
                        function(result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        }, function()
                        {
                            NotifierService.error('Website Filters could not be loaded');
                        }
                    );
                }
            }
        );
    }
);
