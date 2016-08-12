'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
      .state('supporttickets.statuses', {
        url: '/statuses',
        preserveQueryParams: true,
        views: {
          '@': {
            templateUrl: 'src/supporttickets/statuses/list.html',
            controller: 'SupportticketStatusesListController'
          }
        },
        menu: {
          name: 'Statuses',
          class: 'fa fa-check-circle-o',
          tag: 'sidebar',
          priority: 2
        }
      })
  ;
});
