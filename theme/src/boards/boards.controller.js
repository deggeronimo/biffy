'use strict';

angular.module('biffyApp')
    .controller('BoardsController', function ($scope, RestangularAppService, $stateParams, $location, BreadcrumbService) {
        $scope.noWatch = true;
        $scope.board = {};
        $scope.boards = {};
        $scope.unreadThreads = {};
        $scope.id = null;
        $scope.index = true;
        $scope.pagination = {page: 1, perPage: 10, sort: 'time'};

        angular.extend($scope.pagination, $location.search());

        if ($stateParams.boardId) {
            $scope.id = $stateParams.boardId;
            $scope.index = false;
        }

        $scope.getData = function () {
            BreadcrumbService.clear();
            if ($scope.index) {
                RestangularAppService.all('boards').customGET().then(function (data) {
                    $scope.boards = data.boards;
                    $scope.unreadThreads = data.unread;

                    BreadcrumbService.add({text: 'Message Boards'});
                });
            } else {
                $location.search($scope.pagination);
                RestangularAppService.all('boards').customGET('board/' + $scope.id, $scope.pagination).then(function (data) {
                    $scope.noWatch = false;
                    $scope.board = data;
                    $scope.boards = data.children;

                    BreadcrumbService.add({text: 'Message Boards', state: 'boards'});
                    _.each($scope.board.parents, function (parent) {
                        BreadcrumbService.add({text: parent.name, state: 'boards.board', stateParams: {boardId: parent.id}});
                    });
                    BreadcrumbService.add({text: $scope.board.name});
                });
            }
        };

        $scope.getData();

        $scope.$watch('pagination.page', function () {
            if (!$scope.noWatch) {
                $scope.getData();
            }
        });

        $scope.sort = function (method) {
            $scope.pagination.sort = method;
            $scope.getData();
        };

        $scope.moveCategory = function (category, direction) {
            RestangularAppService.all('boards').one('category', category.id).customPUT({move: direction}).then(function () {
                $scope.getData();
            });
        };
    });