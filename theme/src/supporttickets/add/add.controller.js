'use strict';

angular.module('biffyApp')
.controller('SupportticketsAddController', function($q, $scope, $stateParams, $state, RestangularAppService, NotifierService) {

  $scope.data = {};

  $q.all([
    RestangularAppService.all('supportticketstatuses').customGETLIST('select'),
    RestangularAppService.all('supportticketcategories').customGETLIST('select'),
    RestangularAppService.all('userselect').customGETLIST('select')
  ]).then(function(result) {
    $scope.statuses = result[0];
    $scope.categories = result[1];
    $scope.users = result[2];
    $scope.data.status_id = 1; //default status open
  });

  $scope.store = function() {
      RestangularAppService.all('supporttickets').post($scope.data).then(function(data) {
        $state.transitionTo('supporttickets.edit', {id: data[0].id});
      }, function(data) {
          NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
      });
  };

  $scope.cancel = function() {
      $state.transitionTo('supporttickets.list');
  };

});
