'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'devicetypes.options',
            {
                url: '/options',
                views: {
                    '@': {
                        templateUrl: 'src/devicetypes/options/options.html',
                        controller: 'DeviceTypesOptionsController'
                    }
                },
                menu: {
                    name: 'Device Repair Options',
                    class: 'fa fa-gear',
                    tag: 'sidebar',
                    priority: 100
                }
            }
        );
    }
);