'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
    .state('expense', {
      parent: 'store-ops',
      url: '/expense',
      views: {
        '@': {
          templateUrl: 'src/expensereport/list.html',
          controller: 'expenseController'
        }
      },
      menu: {
        name: 'Expense Report',
        class: 'fa fa-usd',
        tag: 'sidebar',
        priority: 50
      }
    })
});
