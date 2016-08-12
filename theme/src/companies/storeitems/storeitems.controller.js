'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'CompaniesStoreItemsController',
    function ($state, $scope, $location, ngTableParams, RestangularAppService, NotifierService)
    {
        $scope.companyId = $state.params.companyId;

        $scope.tableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                        name: 'asc'
                    }
                },
                $location.search()
            ), {
                total: 0,
                getData: function($defer, params)
                {
                    $location.search(params.url());
                    RestangularAppService.one('companies', $scope.companyId).all('storeitems').getList(params.url()).then(
                        function(result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        },
                        function()
                        {
                            NotifierService.error('Company Store Items could not be loaded');
                        }
                    );
                }
            }
        );
    }
);
