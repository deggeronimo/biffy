'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('employees.permissions', {
                url: '/permissions/{id}',
                views: {
                    '@': {
                        templateUrl: 'src/employees/permissions/permissions.html',
                        controller: 'EmployeesPermissionsController'
                    }
                }
            })
        ;
    });
