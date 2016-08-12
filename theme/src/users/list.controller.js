'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('UsersListController', function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService) {

        $scope.stores = RestangularAppService.all('stores').getList().$object;

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
                getData: function($defer, params) {
                    $location.search(params.url());

                    if (params.$params && params.$params.filter && params.$params.filter.store_id) {
                        RestangularAppService.one('stores', params.$params.filter.store_id).getList('users').then(function (result) {
                            $scope.tableParams.settings({total: result.plain().length});
                            $defer.resolve(result);
                        }, function () {
                            NotifierService.error('Users could not be loaded');
                        });
                    } else {
                        RestangularAppService.all('users').getList(params.url()).then(function (result) {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        }, function () {
                            NotifierService.error('Users could not be loaded');
                        });
                    }
                }
            })
        ;
    });
