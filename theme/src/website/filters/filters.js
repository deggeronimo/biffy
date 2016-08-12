'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'website.filters',
            {
                url: '/filters',
                views: {
                    '@': {
                        templateUrl: 'src/website/filters/filters.html',
                        controller: 'WebsiteFiltersController'
                    }
                },
                menu: {
                    name: 'Filters',
                    class: 'fa fa-server',
                    tag: 'sidebar',
                    priority: 50
                }
            }
        );
    }
);
