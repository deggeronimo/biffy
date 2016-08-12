'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('permissions.edit', {
                url: '/edit/{id}',
                views: {
                    '@': {
                        templateUrl: 'src/permissions/edit/edit.html',
                        controller: 'PermissionsEditController'
                    }
                }
            })
        ;
    });
