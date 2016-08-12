'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('roles', {
                parent: 'admin',
                url: '/roles',
                views: {
                    '@': {
                        templateUrl: 'src/roles/list.html',
                        controller: 'RolesListController'
                    }
                },
                menu: {
                    name: 'Roles',
                    class: 'fa fa-user-secret',
                    tag: 'sidebar',
                    priority: 40
                }
            })
        ;
    });
