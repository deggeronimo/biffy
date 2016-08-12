'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('users', {
                parent: 'admin',
                url: '/users',
                views: {
                    '@': {
                        templateUrl: 'src/users/list.html',
                        controller: 'UsersListController'
                    }
                },
                menu: {
                    name: 'Users',
                    class: 'fa fa-user',
                    tag: 'sidebar',
                    priority: 50
                },
                preserveQueryParams: true
            })
        ;
    });
