'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
.controller('SupportticketsListController', function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService) {

  $q.all([
      RestangularAppService.all('supportticketstatuses').customGETLIST('select'),
      RestangularAppService.all('supportticketcategories').customGETLIST('select'),
      RestangularAppService.all('userselect').customGETLIST('select'),
    ]).then(function(result) {
      $scope.statuses = result[0];
      $scope.categories = result[1];
      $scope.users = result[2];
      $scope.tableParams = new ngTableParams(
        angular.extend(
            {
                page: 1,
                count: 10,
                sorting: {
                    id: 'asc'
                },
                filter: {
                  status_id: 1
                }
            },
            $location.search()
        ), {
            total: 0,
            getData: function($defer, params) {
                $location.search(params.url());
                RestangularAppService.all('supporttickets').getList(params.url()).then(function(result) {
                    $scope.tableParams.settings({total: result.paginator.total});
                    $defer.resolve(result);
                }, function() {
                    NotifierService.error('Support tickets could not be loaded');
                });
            }
        })
      ;
  });

});
