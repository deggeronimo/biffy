'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'appointments',
            {
                parent: 'pos',
                url : '/appointments',
                controller : function($state)
                {
                    $state.go('appointments.list');
                },
                menu : {
                    name : 'Appointments',
                    class : 'fa fa-calendar',
                    tag : 'sidebar',
                    priority : 80
                }
            }
        )
        .state(
            'appointments.list',
            {
                url : '/appointments/list',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/appointments/list.html',
                        controller : 'AppointmentListController'
                    }
                },
                menu :
                {
                    name : 'Appointment List',
                    class : 'fa fa-calendar',
                    tag : 'sidebar',
                    priority : 6
                }
            }
        )
        ;
    }
);
