'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('users.permissions', {
                url: '/permissions/{id}',
                views: {
                    '@': {
                        templateUrl: 'src/users/permissions/permissions.html',
                        controller: 'UsersPermissionsController'
                    }
                }
            })
        ;
    });
