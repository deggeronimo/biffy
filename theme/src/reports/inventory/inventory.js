'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('reports.inventory', {
                url: '/reports/inventory/{reportType}',
                views: {
                    '@': {
                        templateUrl: 'src/reports/inventory/inventory.html',
                        controller: 'ReportInventoryController'
                    }
                },
                menu: {
                    name: 'Inventory Reports',
                    class: 'fa fa-cog fa-spin',
                    tag: 'sidebar',
                    priority: 80
                }
            })
        ;
    });
