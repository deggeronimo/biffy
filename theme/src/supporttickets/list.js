'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
      .state('supporttickets', {
          parent: 'support',
        url: '/supporttickets',
        controller: function($state) {
          $state.go('supporttickets.list');
        },
        menu: {
          name: 'Support Tickets',
          class: 'fa fa-support',
          tag: 'sidebar',
          priority: 50
        }
      })
      .state('supporttickets.list', {
        url: '/list',
        preserveQueryParams: true,
        views: {
          '@': {
            templateUrl: 'src/supporttickets/list.html',
            controller: 'SupportticketsListController'
          }
        },
        menu: {
          name: 'List',
          class: 'fa fa-navicon',
          tag: 'sidebar',
          priority: 6
        }
      })
  ;
});
