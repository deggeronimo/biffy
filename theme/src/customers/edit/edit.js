'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
    .state('customers.add', {
      url: '/new',
      views: {
        '@': {
          templateUrl: 'src/customers/edit/edit.html',
          controller: 'CustomersEditController'
        }
      }
    })
    .state('customers.edit', {
      url: '/edit/{id}',
      views: {
        '@': {
          templateUrl: 'src/customers/edit/edit.html',
          controller: 'CustomersEditController'
        }
      }
    })
  ;
});
