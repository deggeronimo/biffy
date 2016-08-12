'use strict';

angular.module('biffyApp').config(
    function ($stateProvider) {
        $stateProvider.state(
            'pos.bohwolist',
            {
                url: '/bohwolist',
                //preserveQueryParams: true, //Re-implement: query params will be preserved going away from this state and applied on coming back
                views: {
                    '@': {
                        templateUrl: 'src/pos/bohwolist/bohwolist.html',
                        controller: 'WoUiController'
                    }
                },
                menu: {
                    name: 'Workorders',
                    class: 'fa fa-edit',
                    tag: 'sidebar',
                    priority: 60
                }
            }
        )
        ;
    }
);
