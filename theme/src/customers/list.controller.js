'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('CustomersListController', function ($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService, ExportService) {

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
                getData: function ($defer, params) {
                    $location.search(params.url());
                    RestangularAppService.all('customers').getList(params.url()).then(function (result) {
                        $scope.tableParams.settings({total: result.paginator.total});
                        $defer.resolve(result);
                    }, function () {
                        NotifierService.error('Customers could not be loaded');
                    });
                }
            })
        ;

        $scope.export = function () {
            ExportService.go('customers', $location.search());
        };
    });
