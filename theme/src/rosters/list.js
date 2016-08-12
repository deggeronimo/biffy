'use strict';

/**
 * List employees is displayed in this state
 * Selecting an employee loads its calendar of roster entries
 */
angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
    .state('rosters', {
      parent: 'store-ops',
      url: '/rosters',
	  preserveQueryParams: true,
	  views: {
		'@': {
		  templateUrl: 'src/rosters/list.html',
		  controller: 'RostersListController'
		}
	  },
      menu: {
        name: 'Roster',
        class: 'fa fa-calendar-o',
        tag: 'sidebar',
        priority: 50
      }
    })
  ;
});
