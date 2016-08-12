'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('boards.thread', {
            url: '/thread/{threadId}',
            views: {
                '@': {
                    templateUrl: 'src/boards/thread/thread.html',
                    controller: 'BoardsThreadController'
                }
            }
        });
    });