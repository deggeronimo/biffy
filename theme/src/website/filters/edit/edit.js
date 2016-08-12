'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'website.filters.edit',
            {
                url: '/edit/{id}',
                views: {
                    '@': {
                        templateUrl: 'src/website/filters/edit/edit.html',
                        controller: 'WebsiteFiltersEditController'
                    }
                }
            })
        ;
    }
);
