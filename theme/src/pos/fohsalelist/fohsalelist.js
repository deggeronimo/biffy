'use strict';

angular.module('biffyApp').config(
    function ($stateProvider) {
        $stateProvider.state(
            'pos.fohsalelist',
            {
                url: '/fohsalelist',
                //preserveQueryParams: true, //Re-implement: query params will be preserved going away from this state and applied on coming back
                views: {
                    '@': {
                        templateUrl: 'src/pos/fohsalelist/fohsalelist.html',
                        controller: 'SaleListController'
                    }
                },
                menu: {
                    name: 'Front of House Sale Lookup',
                    class: 'fa fa-user',
                    tag: 'sidebar',
                    priority: 90
                }
            }
        );
    }
);
