'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
    .state('customers', {
      parent: 'pos',
      url: '/customers',
      preserveQueryParams: true,
      views: {
          '@': {
              templateUrl: 'src/customers/list.html',
              controller: 'CustomersListController'
          }
      },
      menu: {
        name: 'Customers',
        class: 'fa fa-users',
        tag: 'sidebar',
        priority: 50
      }
    });
});
