'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('projects', {
            parent: 'authorized',
            url: '/projects',
            views: {
                '@': {
                    templateUrl: 'src/projects/list.html',
                    controller: 'ProjectsListController'
                }
            },
            menu: {
                name: 'Projects',
                class: 'fa fa-tasks',
                tag: 'sidebar',
                priority: 25
            }
        });
    });