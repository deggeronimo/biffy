'use strict';

angular.module('biffyApp')
    .controller('BoardsEditController', function ($scope, RestangularAppService, $stateParams, $state, BreadcrumbService) {
        $scope.id = $stateParams.boardId;
        $scope.data = {};
        $scope.categoryList = {};

        BreadcrumbService.add({text: 'Message Boards', state: 'boards'});
        BreadcrumbService.add({text: 'Edit'});

        $scope.isAdd = function () {
            return $scope.id === 'new';
        };

        $scope.mode = $scope.isAdd() ? 'Add' : 'Edit';

        RestangularAppService.all('boards').customGET('category-list').then(function (data) {
            $scope.categoryList = data;
        });

        if (!$scope.isAdd()) {
            RestangularAppService.one('boards/category', $scope.id).get().then(function (data) {
                $scope.data = data;
            });
        }

        $scope.store = function () {
            RestangularAppService.all('boards/category').post($scope.data).then(function (data) {
                $state.transitionTo('boards.board', {boardId: data.id});
            });
        };

        $scope.update = function () {
            $scope.data.put().then(function () {
                $state.transitionTo('boards.board', {boardId: $scope.id});
            });
        };

        $scope.cancel = function () {
            if ($scope.isAdd()) {
                $state.transitionTo('boards');
            } else {
                $state.transitionTo('boards.board', {boardId: $scope.id});
            }
        };
    });