'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('groups', {
                parent: 'admin',
                url: '/groups',
                views: {
                    '@': {
                        templateUrl: 'src/groups/list.html',
                        controller: 'GroupsListController'
                    }
                },
                menu: {
                    name: 'Groups',
                    class: 'fa fa-users',
                    tag: 'sidebar',
                    priority: 45
                }
            })
        ;
    });
