'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'appointments.edit',
            {
                url : '/appointments/edit/{id}',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/appointments/edit/edit.html',
                        controller : 'AppointmentEditController'
                    }
                },
                menu :
                {
                    name : 'Appointment Edit',
                    class : 'fa fa-calendar',
                    tag : 'sidebar',
                    priority : 6
                }
            }
        );
    }
);
