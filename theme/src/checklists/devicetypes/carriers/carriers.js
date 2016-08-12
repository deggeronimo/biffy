'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'checklists.devicetypes.carriers',
            {
                url : '/carriers/{id}',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/checklists/devicetypes/carriers/carriers.html',
                        controller : 'ChecklistDeviceTypesCarrierController'
                    }
                }
            }
        );
    }
);
