'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'devicetypes.edit',
            {
                url : '/{deviceTypeId}/edit',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/devicetypes/edit/edit.html',
                        controller : 'DeviceTypesEditController'
                    }
                }
            }
        );
    }
);
