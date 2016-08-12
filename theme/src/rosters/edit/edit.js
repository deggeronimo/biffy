'use strict';

/**
 * Add/Edit form for a single roster
 */
angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
	  .state('rosters.employee.add', {
		url: '/new',
		modal: {
		  size: 'lg',
		  reload: false,
		  broadcast: 'roster.close'
		},
		templateUrl: 'src/rosters/edit/edit.html',
		controller: 'RostersEditController'
	  })
	  .state('rosters.employee.edit', {
		url: '/edit/{rosterId}',
		modal: {
		  size: 'lg',
		  reload: false,
		  broadcast: 'roster.close'
		},
		templateUrl: 'src/rosters/edit/edit.html',
		controller: 'RostersEditController'
	  });
});
