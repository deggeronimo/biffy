'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'inventory.tracking',
            {
                url : '/tracking/{storeItemId}',
                views :
                {
                    '@' :
                    {
                        templateUrl: 'src/inventory/tracking/tracking.html',
                        controller: 'InventoryTrackingController'
                    }
                },
                menu:
                {
                    name: 'Tracking',
                    class: 'fa fa-money',
                    tag: 'sidebar',
                    priority: 50
                }
            }
        );
    }
);

