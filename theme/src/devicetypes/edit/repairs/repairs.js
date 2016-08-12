'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'devicetypes.edit.repairs',
            {
                url : '/repair/{deviceRepairId}/edit',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/devicetypes/edit/repairs/repairs.html',
                        controller : 'DeviceTypesEditRepairsController'
                    }
                }
            }
        );
    }
);
