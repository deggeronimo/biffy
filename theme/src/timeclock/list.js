'use strict';

angular.module('biffyApp')
.config(function($stateProvider) {
    $stateProvider
        .state('timeclock', {
            parent: 'store-ops',
            url: '/timeclock',
            views: {
                '@': {
                    templateUrl: 'src/timeclock/list.html',
                    controller: 'TimeclockListController'
                }
            },
            menu: {
                name: 'Timeclock',
                class: 'fa fa-clock-o',
                tag: 'sidebar',
                priority: 50
            }
        });
});