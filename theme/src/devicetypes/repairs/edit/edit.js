'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'devicetypes.repairs.edit',
            {
                url : '/{deviceRepairTypeId}/edit',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/devicetypes/repairs/edit/edit.html',
                        controller : 'DeviceTypesRepairsEditController'
                    }
                }
            }
        );
    }
);
