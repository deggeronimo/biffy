'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'devicetypes.manufacturers',
            {
                url : '/manufacturers',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/devicetypes/manufacturers/manufacturers.html',
                        controller : 'DeviceTypesManufacturersController'
                    }
                },
                menu :
                {
                    name : 'Device Manufacturers',
                    class : 'fa fa-phone',
                    tag : 'sidebar',
                    priority : 90
                }
            }
        );
    }
);
