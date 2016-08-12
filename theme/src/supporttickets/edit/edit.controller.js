'use strict';

angular.module('biffyApp')
.controller('SupportticketsEditController', function($q, $rootScope, $scope, $stateParams, $state, RestangularAppService, NotifierService, dialogs) {

  $scope.id = $stateParams.id;
  $scope.ticket = {};
  $scope.newItem = {
    support_ticket_id: $scope.id
  };

  $q.all([
    RestangularAppService.all('supportticketstatuses').customGETLIST('select'),
    RestangularAppService.all('supportticketcategories').customGETLIST('select'),
    RestangularAppService.all('userselect').customGETLIST('select'),
    RestangularAppService.one('supporttickets', $scope.id).get()
  ]).then(function(result) {
    $scope.statuses = result[0];
    $scope.categories = result[1];
    $scope.users = result[2];
    $scope.ticket = result[3];
    RestangularAppService.one('supporttickets', $scope.id).all('updates').getList().then(
      function(data) {
        $scope.data = data;
      },
      function(data) {
        NotifierService.error('Could not load updates ' + JSON.stringify(data.data.messages));
      }
    );
  });

  $scope.reload = $rootScope.reload;

  $scope.store = function() {
    RestangularAppService.one('supporttickets', $scope.id).all('updates').post($scope.newItem).then(function() {
      $rootScope.reload();
    }, function(data) {
      NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
    });
  };

  $scope.update = function(item) {
    item.put().then(function() {
      $rootScope.reload();
    }, function(data) {
      NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
    });
  };

  $scope.destroy = function(item) {
    item.remove().then(function() {
      $rootScope.reload();
    }, function(data) {
      NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
    });
  };

  $scope.updateTicket = function() {
    $scope.ticket.put().then(function() {
      $rootScope.reload();
    }, function(data) {
      NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
    });
  };

  $scope.destroyTicket = function() {
    var dlg = dialogs.confirm('Delete support ticket #' + $scope.id, 'Are you sure?');
    dlg.result.then(function() {
      $scope.ticket.remove().then(function() {
        $state.transitionTo('supporttickets.list');
      }, function(data) {
        NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
      });
    });
  };

  $scope.cancel = function() {
    $state.transitionTo('supporttickets.list');
  };

});
