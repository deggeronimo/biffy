'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
    .state('filemanagement.set', {
      url: '/set/{id}',
      templateUrl: 'src/filemanagement/set/set.html',
      controller: 'FilemanagementSetController'
    })
  ;
});
