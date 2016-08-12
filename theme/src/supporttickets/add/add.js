'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
      .state('supporttickets.add', {
        url: '/new',
        views: {
          '@': {
            templateUrl: 'src/supporttickets/add/add.html',
            controller: 'SupportticketsAddController'
          }
        },
        menu: {
          name: 'Add new',
          class: 'fa fa-plus-square-o',
          tag: 'sidebar',
          priority: 4
        }
      })
  ;
});
