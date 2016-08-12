'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'checklists.devicetypes.repairs',
            {
                url : '/repairs/{deviceTypeId}',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/checklists/devicetypes/repairs/repairs.html',
                        controller : 'ChecklistDeviceTypeRepairsController'
                    }
                }
            }
        );
    }
);
