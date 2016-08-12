'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
      .state('supporttickets.categories', {
        url: '/categories',
        preserveQueryParams: true,
        views: {
          '@': {
            templateUrl: 'src/supporttickets/categories/list.html',
            controller: 'SupportticketCategoriesListController'
          }
        },
        menu: {
          name: 'Categories',
          class: 'fa fa-tags',
          tag: 'sidebar',
          priority: 2
        }
      })
  ;
});
