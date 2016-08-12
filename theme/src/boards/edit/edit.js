'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('boards.edit', {
            url: '/edit/{boardId}',
            views: {
                '@': {
                    templateUrl: 'src/boards/edit/edit.html',
                    controller: 'BoardsEditController'
                }
            }
        });
    });