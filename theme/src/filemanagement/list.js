'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
    .state('filemanagement', {
      parent: 'kbase',
      url: '/filemanagement',
      preserveQueryParams: true,
      views: {
        '@': {
          templateUrl: 'src/filemanagement/list.html',
          controller: 'FilemanagementListController'
        }
      },
      menu: {
        name: 'File Management',
        class: 'fa fa-file-image-o',
        tag: 'sidebar',
        priority: 50
      }
    })
  ;
});
