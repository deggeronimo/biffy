'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('users.edit', {
                url: '/edit/{id}',
                views: {
                    '@': {
                        templateUrl: 'src/users/edit/edit.html',
                        controller: 'UsersEditController'
                    }
                }
            })
        ;
    });
