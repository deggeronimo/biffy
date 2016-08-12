'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
.controller('RostersListController', function($rootScope, $state, $scope, $location, RestangularAppService, NotifierService, StoreService) {

  $scope.refresh = function() {
	if(!StoreService.current.id) return;
	RestangularAppService.one('stores', StoreService.current.id).all('users').getList().then(function(result) {
	  $scope.employees = result;
	  if(result.length > 0 && !$location.path().match(/\/employee\//gi)) {
		$state.transitionTo('rosters.employee', {employeeId: result[0].id})
	  }
	}, function() {
	  NotifierService.info("No employee available cannot load rosters");
	});
  };

  $scope.refresh();

  // Also refresh on each Store selection
  $rootScope.$on('store.select', function(event, stores) {
	$scope.refresh();
  });

});
