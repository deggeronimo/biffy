'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
      .state('leads.add', {
        url: '/new',
        views: {
          '@': {
            templateUrl: 'src/leads/edit/edit.html',
            controller: 'LeadsEditController'
          }
        }
      })
      .state('leads.edit', {
        url: '/edit/{id}',
        views: {
          '@': {
            templateUrl: 'src/leads/edit/edit.html',
            controller: 'LeadsEditController'
          }
        }
      })
  ;
});
