'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('projects.view', {
            url: '/{projectId}?task&subtask',
            views: {
                '@': {
                    templateUrl: 'src/projects/view/view.html',
                    controller: 'ProjectsViewController'
                }
            },
            reloadOnSearch: false,
            preserveQueryParams: true
        });
    });