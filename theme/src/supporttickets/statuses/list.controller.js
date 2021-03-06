'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
.controller('SupportticketStatusesListController', function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService) {

  $scope.newData = {};

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
              RestangularAppService.all('supportticketstatuses').getList(params.url()).then(function(result) {
                  $scope.tableParams.settings({total: result.paginator.total});
                  $defer.resolve(result);
              }, function() {
                  NotifierService.error('Statuses could not be loaded');
              });
          }
      })
  ;

  $scope.store = function() {
    RestangularAppService.all('supportticketstatuses').post($scope.newData).then(function() {
      $state.transitionTo('supporttickets.statuses', {}, {reload: true, inherit: false, notify: true});
    }, function(data) {
      NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
    });
  };

  $scope.destroy = function(item) {
    item.remove().then(function() {
      $state.transitionTo('supporttickets.statuses', {}, {reload: true, inherit: false, notify: true});
    }, function(data) {
      NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
    });
  };

});
