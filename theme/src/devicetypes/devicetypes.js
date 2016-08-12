'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'devicetypes',
            {
                parent: 'data-management',
                url : '/devicetypes',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/devicetypes/devicetypes.html',
                        controller : 'DeviceTypesController'
                    }
                },
                menu :
                {
                    name : 'Device Types',
                    class : 'fa fa-phone',
                    tag : 'sidebar',
                    priority : 90
                }
            }
        );
    }
);
