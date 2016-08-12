'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'marketing',
            {
                parent: 'store-ops',
                url: '/marketing',
                views: {
                    '@': {
                        templateUrl: 'src/marketingrun/marketing.html',
                        controller: 'MarketingController'
                    }
                },
                menu: {
                    name: 'Marketing Run',
                    class: 'fa fa-car',
                    tag: 'sidebar',
                    priority: 80
                }
            }
        )
    }
);
