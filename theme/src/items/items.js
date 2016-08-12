'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
    .state('items', {
      parent: 'admin',
      url: '/items',
      views: {
        '@': {
          templateUrl: 'src/items/items.html',
          controller: 'itemsController'
        }
      },
      menu: {
        name: 'Items',
        class: 'fa fa-cubes',
        tag: 'sidebar',
        priority: 50
      }
    })
});
