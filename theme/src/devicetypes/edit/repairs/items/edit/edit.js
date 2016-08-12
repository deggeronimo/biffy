'use strict';

angular.module('biffyApp').config(
    function ($stateProvider)
    {
        $stateProvider.state(
            'devicetypes.edit.repairs.items.edit',
            {
                url: '/{repairOptionId}/edit',
                preserveQueryParams: true,
                views: {
                    '@': {
                        templateUrl: 'src/devicetypes/edit/repairs/items/edit/edit.html',
                        controller: 'DeviceTypesRepairsItemsEditController'
                    }
                }
            }
        );
    }
);