'use strict';

angular.module('biffyApp').config(
    function ($stateProvider) {
        $stateProvider.state(
            'pos.checkout',
            {
                url: '/checkout/{saleId}',
                preserveQueryParams: true, //Re-implement: query params will be preserved going away from this state and applied on coming back
                views: {
                    '@': {
                        templateUrl: 'src/pos/checkout/checkout.html',
                        controller: 'SaleEditController'
                    }
                }
            }
        );
    }
);
