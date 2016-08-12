'use strict';

angular.module('biffyApp')
.run(function($rootScope, $location) {
  $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
    //console.log('stateChangeStart', fromState.name, toState.name);
    if(fromState.preserveQueryParams) { //preserve the query params when fromState supports it
      fromState.queryParams = angular.copy($location.search());
      //console.log('Preserved', fromState.name, fromState.queryParams);
    }
  });
  $rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams) {
    if((!!toState.preserveQueryParams) && angular.isDefined(toState.queryParams)) {
      $location.search(toState.queryParams);
      //console.log('Applied', toState.name, toState.queryParams);
      delete toState.queryParams;
    }
  });
});
