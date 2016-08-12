'use strict';

angular.module('biffyApp')
    .controller('BoardsThreadEditController', function ($stateParams, $state, $scope, RestangularAppService, BreadcrumbService) {
        $scope.board = {};
        $scope.thread = {};

        $scope.isAdd = function () {
            return !$stateParams.threadId;
        };

        $scope.doBreadcrumbs = function () {
            BreadcrumbService.add({text: 'Message Boards', state: 'boards'});
            _.each($scope.board.parents, function (parent) {
                BreadcrumbService.add({text: parent.name, state: 'boards.board', stateParams: {boardId: parent.id}});
            });
            BreadcrumbService.add({text: $scope.board.name, state: 'boards.board', stateParams: {boardId: $scope.board.id}});
            BreadcrumbService.add({text: $scope.mode + ' Thread'});
        };

        if ($scope.isAdd()) {
            $scope.mode = 'Add';

            RestangularAppService.all('boards').customGET('board/' + $stateParams.boardId).then(function (data) {
                $scope.board = data;
                $scope.thread.category_id = $scope.board.id;
                $scope.doBreadcrumbs();
            });
        } else {
            $scope.mode = 'Edit';

            RestangularAppService.one('boards/thread', $stateParams.threadId).get({edit: true}).then(function (data) {
                $scope.board = data.category;
                $scope.thread = data;
                $scope.thread.post = data.first_post.content;
                $scope.doBreadcrumbs();
            });
        }

        $scope.store = function () {
            RestangularAppService.all('boards/thread').post($scope.thread).then(function (data) {
                $state.transitionTo('boards.thread', {threadId: data.id});
            });
        };

        $scope.update = function () {
            $scope.thread.first_post.content = $scope.thread.post;
            $scope.thread.put().then(function () {
                $state.transitionTo('boards.thread', {threadId: $scope.thread.id});
            });
        };

        $scope.cancel = function () {
            if ($scope.isAdd()) {
                $state.transitionTo('boards.board', {boardId: $scope.board.id});
            } else {
                $state.transitionTo('boards.thread', {threadId: $scope.thread.id});
            }
        };
    });