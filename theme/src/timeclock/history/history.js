'use strict';

angular.module('biffyApp')

.config(function($stateProvider) {
    $stateProvider
        .state('timeclock.history', {
            url: '/history',
            views: {
                '@': {
                    templateUrl: 'src/timeclock/history/history.html',
                    controller: 'TimeclockHistoryController'
                }
            },
            menu: {
                name: 'History',
                class: 'fa fa-clock-o',
                tag: 'sidebar',
                priority: 50
            }
        });
});