'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
.controller('RostersRolesController', function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService) {

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
              RestangularAppService.all('rosterroles').getList(params.url()).then(function(result) {
                  $scope.tableParams.settings({total: result.paginator.total});
                  $defer.resolve(result);
              }, function() {
                  NotifierService.error('Roles could not be loaded');
              });
          }
      })
  ;

  $scope.store = function() {
    RestangularAppService.all('rosterroles').post($scope.newData).then(function() {
      $state.transitionTo('rosters.roles', {}, {reload: true, inherit: false, notify: true});
    }, function(data) {
      NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
    });
  };

  $scope.destroy = function(item) {
    item.remove().then(function() {
      $state.transitionTo('rosters.roles', {}, {reload: true, inherit: false, notify: true});
    }, function(data) {
      NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
    });
  };

});
