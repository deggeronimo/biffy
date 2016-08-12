'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('reports.sales', {
                url: '/reports/sales/{reportType}',
                views: {
                    '@': {
                        templateUrl: 'src/reports/sales/sales.html',
                        controller: 'ReportSalesController'
                    }
                },
                menu: {
                    name: 'Sales Reports',
                    class: 'fa fa-cog fa-spin',
                    tag: 'sidebar',
                    priority: 80
                }
            })
        ;
    });
