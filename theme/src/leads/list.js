'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
  $stateProvider
      .state('leads', {
      parent: 'pos',
        url: '/leads',
          views: {
              '@': {
                  templateUrl: 'src/leads/list.html',
                  controller: 'LeadsListController'
              }
          },
        menu: {
          name: 'Leads',
          class: 'fa fa-male',
          tag: 'sidebar',
          priority: 50
        }
      });
});
