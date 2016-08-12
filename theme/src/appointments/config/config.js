'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'appointments.config',
            {
                url : '/appointments/config',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/appointments/config/config.html',
                        controller : 'AppointmentConfigController'
                    }
                },
                menu :
                {
                    name : 'Config Appointment',
                    class : 'fa fa-calendar',
                    tag : 'sidebar',
                    priority : 6
                }
            }
        );
    }
);
