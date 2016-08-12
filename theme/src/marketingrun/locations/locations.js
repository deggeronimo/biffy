'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'marketing.locations',
            {
                url: '/locations',
                views: {
                    '@': {
                        templateUrl: 'src/marketingrun/locations/locations.html',
                        controller: 'MarketingLocationsController'
                    }
                },
                menu: {
                    name: 'Marketing Run Locations',
                    class: 'fa fa-car',
                    tag: 'sidebar',
                    priority: 60
                }
            }
        );
    }
);
