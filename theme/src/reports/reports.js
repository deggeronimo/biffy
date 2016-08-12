'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('reports', {
                url: '/reports',
                views: {
                    '@': {
                        templateUrl: 'src/reports/reports.html',
                        controller: 'ReportHomeController'
                    }
                },
                menu: {
                    name: 'Reports',
                    class: 'fa fa-line-chart',
                    tag: 'sidebar',
                    priority: 100
                }
            })
        ;
    });
