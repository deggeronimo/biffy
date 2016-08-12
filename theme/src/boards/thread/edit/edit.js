'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider
            .state('boards.thread.edit', {
                url: '/edit',
                views: {
                    '@': {
                        templateUrl: 'src/boards/thread/edit/edit.html',
                        controller: 'BoardsThreadEditController'
                    }
                }
            })
            .state('boards.board.thread', {
                url: '/thread',
                views: {
                    '@': {
                        templateUrl: 'src/boards/thread/edit/edit.html',
                        controller: 'BoardsThreadEditController'
                    }
                }
            });
    });