'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'checklists.devicetypes.repairs.add',
            {
                url: '/new',
                preserveQueryParams : true,
                views: {
                    '@': {
                        templateUrl : 'src/checklists/devicetypes/repairs/edit/edit.html',
                        controller : 'ChecklistDeviceTypeRepairsEditController'
                    }
                }
            }
        ).state(
            'checklists.devicetypes.repairs.edit',
            {
                url: '/edit/{id}',
                preserveQueryParams : true,
                views: {
                    '@': {
                        templateUrl : 'src/checklists/devicetypes/repairs/edit/edit.html',
                        controller : 'ChecklistDeviceTypeRepairsEditController'
                    }
                }
            })
        ;
    });
