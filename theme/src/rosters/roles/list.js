'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
      .state('rosters.roles', {
        url: '/roles',
        preserveQueryParams: true,
        views: {
          '@': {
            templateUrl: 'src/roster/roles/list.html',
            controller: 'RostersRolesController'
          }
        },
        menu: {
          name: 'Roles',
          class: 'fa fa-tags',
          tag: 'sidebar',
          priority: 2
        }
      })
  ;
});
