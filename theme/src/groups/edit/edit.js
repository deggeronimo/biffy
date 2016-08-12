'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('groups.edit', {
                url: '/edit/{id}',
                views: {
                    '@': {
                        templateUrl: 'src/groups/edit/edit.html',
                        controller: 'GroupsEditController'
                    }
                }
            })
        ;
    });
