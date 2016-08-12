'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
      .state('supporttickets.edit', {
        url: '/edit/{id}',
        views: {
          '@': {
            templateUrl: 'src/supporttickets/edit/edit.html',
            controller: 'SupportticketsEditController'
          }
        }
      })
  ;
});
