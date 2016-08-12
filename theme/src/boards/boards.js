'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider
            .state('boards', {
                parent: 'kbase',
                url: '/boards',
                views: {
                    '@': {
                        templateUrl: 'src/boards/boards.html',
                        controller: 'BoardsController'
                    }
                },
                menu: {
                    name: 'Message Boards',
                    class: 'fa fa-comments',
                    tag: 'sidebar',
                    priority: 100
                }
            })
            .state('boards.board', {
                url: '/{boardId}',
                views: {
                    '@': {
                        templateUrl: 'src/boards/boards.html',
                        controller: 'BoardsController'
                    }
                }
            });
    });