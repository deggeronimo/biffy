'use strict';

angular.module('biffyApp').config(
    function ($stateProvider) {
        $stateProvider.state(
            'pos',
            {
                parent: 'authorized',
                url: '/pos',
                controller: function ($state) {
                    $state.go('pos.home');
                },
                menu: {
                    name: 'Point of Sale',
                    class: 'fa fa-money',
                    tag: 'sidebar',
                    priority: 150
                }
            }
        ).state(
            'pos.home',
            {
                url: '/home',
                //preserveQueryParams: true, //Re-implement: query params will be preserved going away from this state and applied on coming back
                views: {
                    '@': {
                        templateUrl: 'src/pos/pos.html',
                        controller: 'PosMainController'
                    }
                }
            }
        );
    }
);
