'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'devicetypes.repairs',
            {
                url : '/repairs',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/devicetypes/repairs/repairs.html',
                        controller : 'DeviceTypesRepairsController'
                    }
                },
                menu :
                {
                    name : 'Device Repair Templates',
                    class : 'fa fa-phone',
                    tag : 'sidebar',
                    priority : 90
                }

            }
        );
    }
);
