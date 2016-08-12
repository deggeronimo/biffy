'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('users.groups', {
                url: '/groups/{id}',
                views: {
                    '@': {
                        templateUrl: 'src/users/groups/groups.html',
                        controller: 'UsersGroupsController'
                    }
                }
            })
        ;
    });
