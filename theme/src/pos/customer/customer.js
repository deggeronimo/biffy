'use strict';

angular.module('biffyApp').config(
    function ($stateProvider) {
        $stateProvider.state(
            'pos.customer',
            {
                url: '/customer',
                //preserveQueryParams: true, //Re-implement: query params will be preserved going away from this state and applied on coming back
                views: {
                    '@': {
                        templateUrl: 'src/pos/customer/customer.html',
                        controller: 'CustomerInfoController'
                    }
                },
                menu: {
                    name: 'Customer Info',
                    class: 'fa fa-user',
                    tag: 'sidebar',
                    priority: 90
                }
            }
        );
    }
);
