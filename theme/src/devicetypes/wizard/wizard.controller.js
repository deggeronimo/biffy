'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'devicetypes.wizard',
            {
                url : '/wizard',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/devicetypes/wizard.html',
                        controller : 'DeviceTypesWizardController'
                    }
                }
            }
        );
    }
);
