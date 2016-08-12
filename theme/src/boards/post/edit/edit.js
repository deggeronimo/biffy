'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('boards.post.edit', {
            url: '/{postId}/edit',
            views: {
                '@': {
                    templateUrl: 'src/boards/post/edit/edit.html',
                    controller: 'BoardsPostEditController'
                }
            }
        });
    });