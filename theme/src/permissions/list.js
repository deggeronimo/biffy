'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('permissions', {
                parent: 'admin',
                url: '/permissions',
                views: {
                    '@': {
                        templateUrl: 'src/permissions/list.html',
                        controller: 'PermissionsListController'
                    }
                },
                menu: {
                    name: 'Permissions',
                    class: 'fa fa-user-secret',
                    tag: 'sidebar',
                    priority: 40
                }
            })
        ;
    });
