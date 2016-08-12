'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'checklists.functions-devicetypes.edit',
            {
                url : '/edit/{id}',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/checklists/functions/devicetypes/edit/edit.html',
                        controller : 'ChecklistFunctionsDeviceTypesEditController'
                    }
                }
            }
        );
    }
);
