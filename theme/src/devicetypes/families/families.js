'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'devicetypes.families',
            {
                url : '/families',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/devicetypes/families/families.html',
                        controller : 'DeviceTypesFamiliesController'
                    }
                },
                menu :
                {
                    name : 'Device Families',
                    class : 'fa fa-phone',
                    tag : 'sidebar',
                    priority : 90
                }
            }
        );
    }
);
