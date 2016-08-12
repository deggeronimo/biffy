'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'checklists.items-devicetypes.edit',
            {
                url : '/edit/{id}',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/checklists/items/devicetypes/edit/edit.html',
                        controller : 'ChecklistItemsDeviceTypesEditController'
                    }
                }
            }
        );
    }
);
