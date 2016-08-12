'use strict';

/**
 * Celdnar view for roster entries of selected employee
 */
angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
    .state('rosters.employee', {
      url: '/employee/{employeeId}',
      views: {
        'calendar': {
          templateUrl: 'src/rosters/employee/employee.html',
          controller: 'RostersEmployeeController'
        }
      }
    })
  ;
});
