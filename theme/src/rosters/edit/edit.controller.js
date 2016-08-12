'use strict';

angular.module('biffyApp').controller('RostersEditController',
	function($q, $scope, $rootScope, $state, RestangularAppService, NotifierService, $moment, modalParams)
	{

	  $scope.id = modalParams.rosterId || null;
	  $scope.data = {
		employee_id: modalParams.employeeId,
		start_time: new Date(),
		time_interval: 0,
		allowed_break: 0
	  };
	  $scope.mode = 'Loading';

	  $scope.isAdd = function()
	  {
		return $scope.id === null;
	  };

	  $scope.isEdit = function()
	  {
		return $scope.mode === 'Edit';
	  };

	  $q.all([
			RestangularAppService.all('rosterroles').customGETLIST('select')
		  ]).then(function(result)
	  {
		$scope.roster_roles = result[0];

		if($scope.isAdd())
		{
		  $scope.mode = 'Add';
		} else {
		  RestangularAppService.one('rosters', $scope.id).get().then(
			  function(data)
			  {
				$scope.mode = 'Edit';
				$scope.data = data;
				$scope.data.start_time = new Date($scope.data.start_time);
			  },
			  function(data)
			  {
				NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
			  }
		  );
		}
	  });

	  $scope.store = function()
	  {
		RestangularAppService.all('rosters').post($scope.data).then(
			function()
			{
			  $scope.$close(true);
			},
			function(data)
			{
			  NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
			}
		);
	  };

	  $scope.update = function()
	  {
		$scope.data.put().then(
			function()
			{
			  $scope.$close(true);
			},
			function(data)
			{
			  NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
			}
		);
	  };

	  $scope.destroy = function()
	  {
		$scope.data.remove().then(
			function()
			{
			  $scope.$close(true);
			},
			function(data)
			{
			  NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
			}
		);
	  };

	  $scope.cancel = function()
	  {
		$scope.$dismiss();
	  };

	  $scope.formatMinutes = function(mins) {
		return parseInt(mins) + " minutes";
	  };

	  $scope.formatEndTime = function(mins) {
		if(mins && isDefined($scope.data.start_time)) {
		  return $moment($scope.data.start_time).add(mins, "minutes").format("MMM D, YYYY hh:mm a");
		} else {
		  return "";
		}
	  };

	}
);
