'use strict';

angular.module('biffyApp')
    .controller('BoardsPostEditController', function ($stateParams, $state, $scope, RestangularAppService, BreadcrumbService) {
        $scope.board = {};
        $scope.post = {};

        RestangularAppService.one('boards/post', $stateParams.postId).get({edit: true}).then(function (data) {
            $scope.board = data.thread.category;
            $scope.post = data;

            BreadcrumbService.add({text: 'Message Boards', state: 'boards'});
            _.each($scope.board.parents, function (parent) {
                BreadcrumbService.add({text: parent.name, state: 'boards.board', stateParams: {boardId: parent.id}});
            });
            BreadcrumbService.add({text: $scope.board.name, state: 'boards.board', stateParams: {boardId: $scope.board.id}});
            BreadcrumbService.add({text: 'Edit Post'});
        });

        $scope.update = function () {
            $scope.post.put().then(function () {
                // todo transition to proper page
                $state.transitionTo('boards.thread', {threadId: $scope.post.thread_id});
            });
        };

        $scope.cancel = function () {
            // todo transition to proper page
            $state.transitionTo('boards.thread', {threadId: $scope.post.thread_id});
        };
    });