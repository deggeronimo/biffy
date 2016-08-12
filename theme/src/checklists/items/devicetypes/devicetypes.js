'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'checklists.items-devicetypes',
            {
                url : '/devicetypes/items',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/checklists/items/devicetypes/devicetypes.html',
                        controller : 'ChecklistItemsDeviceTypesController'
                    }
                },
                menu :
                {
                    name : 'With Device + Device',
                    class : 'fa fa-check',
                    tag : 'sidebar',
                    priority : 6
                }
            }
        );
    }
);
