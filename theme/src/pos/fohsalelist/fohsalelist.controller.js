'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'SaleListController',
    function($scope, $global, RestangularAppService, ngTableParams, $location, NotifierService)
    {
        $global.set('setMainBG', false);

        $scope.tableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                        created_at: 'asc'
                    }
                },
                $location.search()
            ),
            {
                total: 0,
                getData: function($defer, params)
                {
                    $location.search(params.url());

                    RestangularAppService.all('sales').getList(params.url()).then(
                        function(result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        },
                        function()
                        {
                            NotifierService.error('Sales could not be loaded');
                        }
                    );
                }
            }
        );
    }
);

