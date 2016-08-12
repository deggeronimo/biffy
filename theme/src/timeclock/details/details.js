'use strict';

angular.module('biffyApp')

    .config(function($stateProvider) {
        $stateProvider
            .state('timeclock.details', {
                url: '/details/{id}?from&to',
                views: {
                    '@': {
                        templateUrl: 'src/timeclock/details/details.html',
                        controller: 'TimeclockDetailsController'
                    }
                }
            });
    });