'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
.controller('RostersEmployeeController', function($rootScope, $state, $scope, $stateParams, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService, StoreService, uiCalendarConfig, $moment) {

	$scope.rosters = [];
	$scope.events = [];

	$scope.params = {
	  page: 1,
	  count: 100,
	  filter: {},
	  sorting: {
		start_time: 'asc'
	  }
	};

	var startMoment = $moment().startOf('week').subtract(7, 'days');
	var endMoment = $moment().startOf('week').add(14, 'days');

	$scope.refresh = function(start, end) {
	  if( isDefined(start) ) startMoment = $moment(start);
	  if( isDefined(end) ) endMoment = $moment(end);

	  if(!StoreService.current.id || !$stateParams.employeeId) return;
	  $scope.params.filter.store_id = StoreService.current.id;
	  $scope.params.filter.employee_id = $stateParams.employeeId;
	  $scope.params.filter.start_time_begin = startMoment.format('YYYY-MM-DD');
	  $scope.params.filter.start_time_end = endMoment.format('YYYY-MM-DD');
	  RestangularAppService.all('rosters').getList(flattenParams($scope.params)).then(function(result) {
		$scope.rosters = result;
		$scope.events.length = 0; // Clean and refill up events
		forEach($scope.rosters, function(roster) {
		  var startTime = $moment(roster.start_time);
		  var endTime = $moment(startTime).add(roster.time_interval, 'm');
		  var event = {
			id: roster.id,
			title: roster.title,
			start: startTime.toDate(),
			end: endTime.toDate(),
			allDay: false,
			url: '/rosters/employee/' + $scope.params.filter.employee_id + '/edit/' + roster.id
		  };
		  $scope.events.push(event);
		});
	  }, function() {
		NotifierService.error("Could not load rosters");
	  });
	};

	$scope.refresh();

	// Also refresh on each Store selection
	$scope.$on('store.select', function(event, stores) {
	  $scope.uiConfig = {
		calendar: extend($scope.uiConfig.calendar, {
		  firstDay: StoreService.config('pay-period-start') || 0
		})
	  };
	  $scope.refresh();
	});

	$scope.$on('roster.close', function(event) {
	  $scope.refresh();
	});

	$scope.uiConfig = {
	  calendar: {
		scrollTime: '09:00',
		contentHeight: 560,
		defaultView: 'agendaWeek'
	  }
	};

	$scope.eventSources = [ $scope.events, $scope.refresh ];

	$scope.showAll = function() {
	  $scope.uiConfig.calendar.height = 'auto';
	}

});
