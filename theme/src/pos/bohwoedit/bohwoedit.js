'use strict';

angular.module('biffyApp').config(
    function ($stateProvider) {
        $stateProvider.state(
            'pos.woedit',
            {
                url: '/bohwoedit/{workOrderId}',
                //preserveQueryParams: true, //Re-implement: query params will be preserved going away from this state and applied on coming back
                views: {
                    '@': {
                        templateUrl: 'src/pos/bohwoedit/bohwoedit.html',
                        controller: 'WorkOrderEditController'
                    }
                }
            }
        );
    }
);
