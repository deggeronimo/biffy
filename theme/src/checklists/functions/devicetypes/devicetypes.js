'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'checklists.functions-devicetypes',
            {
                url : '/devicetypes/functions',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/checklists/functions/devicetypes/devicetypes.html',
                        controller : 'ChecklistFunctionsDeviceTypesController'
                    }
                },
                menu :
                {
                    name : 'Functions + Device',
                    class : 'fa fa-check',
                    tag : 'sidebar',
                    priority : 6
                }
            }
        );
    }
);
