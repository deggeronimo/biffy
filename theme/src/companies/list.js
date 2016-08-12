'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
    .state('companies', {
      parent: 'pos',
      url: '/companies',
          preserveQueryParams: true,
          views: {
              '@': {
                  templateUrl: 'src/companies/list.html',
                  controller: 'CompaniesListController'
              }
          },
      menu: {
        name: 'Companies',
        class: 'fa fa-building-o',
        tag: 'sidebar',
        priority: 80
      }
    });
});
