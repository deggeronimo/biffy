'use strict';

angular.module('biffyApp').config(
    function ($stateProvider) {
        $stateProvider.state(
            'pos.device',
            {
                url: '/device/{action}/{customerId}/{deviceId}/{saleId}',
                preserveQueryParams: false, //Re-implement: query params will be preserved going away from this state and applied on coming back
                views: {
                    '@': {
                        templateUrl: 'src/pos/device/device.html',
                        controller: 'DeviceSelectionController'
                    }
                }
            }
        );
    }
);
