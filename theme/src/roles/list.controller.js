'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('RolesListController', function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService) {

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
                getData: function($defer, params) {
                    $location.search(params.url());
                    RestangularAppService.all('roles').getList(params.url()).then(function(result) {
                        $scope.tableParams.settings({total: result.paginator.total});
                        $defer.resolve(result);
                    }, function() {
                        NotifierService.error('Roles could not be loaded');
                    });
                }
            })
        ;
    });
