'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'marketing.locations.edit',
            {
                url: '/edit/{id}',
                views: {
                    '@': {
                        templateUrl: 'src/marketingrun/locations/edit/edit.html',
                        controller: 'MarketingLocationEditController'
                    }
                }
            }
        ).state(
            'marketing.locations.add',
            {
                url: '/new',
                views: {
                    '@': {
                        templateUrl: 'src/marketingrun/locations/edit/edit.html',
                        controller: 'MarketingLocationEditController'
                    }
                },
                menu: {
                    name: 'Add new',
                    class: 'fa fa-car',
                    tag: 'sidebar',
                    priority: 4
                }
            }
        );
    }
);
