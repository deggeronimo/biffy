'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('employees', {
                parent: 'store-ops',
                url: '/employees',
                views: {
                    '@': {
                        templateUrl: 'src/employees/list.html',
                        controller: 'EmployeesListController'
                    }
                },
                menu: {
                    name: 'Employees',
                    class: 'fa fa-user',
                    tag: 'sidebar',
                    priority: 50
                }
            })
        ;
    });
