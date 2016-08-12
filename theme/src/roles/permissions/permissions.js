'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('roles.permissions', {
                url: '/permissions/{id}',
                views: {
                    '@': {
                        templateUrl: 'src/roles/permissions/permissions.html',
                        controller: 'RolesPermissionsController'
                    }
                }
            })
        ;
    });
