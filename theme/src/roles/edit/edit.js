'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('roles.edit', {
                url: '/edit/{id}',
                views: {
                    '@': {
                        templateUrl: 'src/roles/edit/edit.html',
                        controller: 'RolesEditController'
                    }
                }
            })
        ;
    });
