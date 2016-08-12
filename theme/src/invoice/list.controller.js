'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'InvoiceListController',
    function($scope, RestangularAppService, ngTableParams, $location, NotifierService)
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
            ), {
                total: 0,
                getData: function($defer, params)
                {
                    $location.search(params.url());
                    RestangularAppService.all('invoices').getList(params.url()).then(
                        function(result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        },
                        function()
                        {
                            NotifierService.error('Invoices could not be loaded');
                        }
                    );
                }
            }
        );
    }
);

