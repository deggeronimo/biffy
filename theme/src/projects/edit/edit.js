'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('projects.edit', {
            url: '/edit/{projectId}',
            views: {
                '@': {
                    templateUrl: 'src/projects/edit/edit.html',
                    controller: 'ProjectsEditController'
                }
            }
        });
    });