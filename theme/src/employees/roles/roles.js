'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('employees.roles', {
                url: '/roles/{id}',
                views: {
                    '@': {
                        templateUrl: 'src/employees/roles/roles.html',
                        controller: 'EmployeesRolesController'
                    }
                }
            })
        ;
    });
