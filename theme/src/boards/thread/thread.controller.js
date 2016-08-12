'use strict';

angular.module('biffyApp')
    .controller('BoardsThreadController', function ($stateParams, $state, $scope, RestangularAppService, UserService, $location, $anchorScroll, $timeout, BreadcrumbService) {
        $scope.noWatch = true;
        $scope.threadId = $stateParams.threadId;
        $scope.board = {};
        $scope.thread = {};
        $scope.newPost = {content: ''};
        $scope.showModerationTools = false;
        $scope.categoryList = {};
        $scope.threadMove = {};
        $scope.pagination = {page: 1, perPage: 10, sort: 'time'};
        $anchorScroll.yOffset = $('header');

        angular.extend($scope.pagination, $location.search());

        $scope.currentUser = UserService.getUser();
        if ($scope.currentUser.admin) {
            $scope.showModerationTools = true;
            RestangularAppService.all('boards').customGET('category-list').then(function (data) {
                $scope.categoryList = data;
            });
        }

        $scope.getData = function (viewed, postId) {
            BreadcrumbService.clear();
            var queryParams = {};
            if (isDefined(viewed) && viewed) {
                queryParams.viewed = true;
            }
            if (isDefined(postId)) {
                $scope.thread.num_posts++;
                $scope.noWatch = true;
                $scope.pagination.page = Math.ceil($scope.thread.num_posts / $scope.pagination.perPage);
            }
            $location.search($scope.pagination);
            angular.extend(queryParams, $scope.pagination);
            RestangularAppService.one('boards/thread', $scope.threadId).get(queryParams).then(function (data) {
                $scope.noWatch = false;
                $scope.board = data.category;
                $scope.thread = data;
                $scope.thread.first_post.user_id = $scope.thread.user_id;
                $scope.threadMove = {};
                if (postId) {
                    $location.hash(postId);
                }
                $timeout(function () { $anchorScroll(); }, 100, false);

                BreadcrumbService.add({text: 'Message Boards', state: 'boards'});
                _.each($scope.board.parents, function (parent) {
                    BreadcrumbService.add({text: parent.name, state: 'boards.board', stateParams: {boardId: parent.id}});
                });
                BreadcrumbService.add({text: $scope.board.name, state: 'boards.board', stateParams: {boardId: $scope.board.id}});
            });
        };

        $scope.getData(true);

        $scope.$watch('pagination.page', function (newV, oldV) {
            if (!$scope.noWatch && parseInt(newV) !== parseInt(oldV)) {
                $location.hash('');
                $scope.getData();
            }
        });

        $scope.sort = function (method) {
            $scope.pagination.sort = method;
            $scope.getData();
        };

        $scope.addPost = function () {
            $scope.newPost.thread_id = $scope.threadId;
            RestangularAppService.all('boards/post').post($scope.newPost).then(function (data) {
                $scope.getData(false, data.id);
                $scope.newPost = {};
            });
        };

        $scope.quotePost = function (post) {
            $location.hash('editor');
            $anchorScroll();
            $scope.newPost.content += '<blockquote>' + post.content + '</blockquote><p><br></p>';
        };

        $scope.deleteThread = function () {
            $scope.thread.remove().then(function () {
                $state.transitionTo('boards.board', {boardId: $scope.board.id});
            });
        };

        $scope.deletePost = function (postId) {
            RestangularAppService.all('boards').one('post', postId).remove().then(function () {
                $location.hash('');
                $scope.getData();
            });
        };

        $scope.updateThreadClosed = function (closed) {
            RestangularAppService.all('boards').one('thread', $scope.thread.id).customPUT({closed: closed}).then(function () {
                $scope.getData();
            });
        };

        $scope.openThread = function () {
            $scope.updateThreadClosed(0);
        };

        $scope.closeThread = function () {
            $scope.updateThreadClosed(1);
        };

        $scope.updateThreadStickied = function (sticky) {
            RestangularAppService.all('boards').one('thread', $scope.thread.id).customPUT({sticky: sticky}).then(function () {
                $scope.getData();
            });
        };

        $scope.stickyThread = function () {
            $scope.updateThreadStickied(1);
        };

        $scope.unstickyThread = function () {
            $scope.updateThreadStickied(0);
        };

        $scope.moveThread = function () {
            if ($scope.threadMove.categoryId) {
                RestangularAppService.all('boards').one('thread', $scope.thread.id).customPUT({move: $scope.threadMove.categoryId}).then(function () {
                    $scope.getData();
                });
            }
        };

        $scope.reportPost = function (post) {
            // todo
        };

        $scope.subscribe = function (payload) {
            if (isUndefined(payload)) {
                payload = {};
            }

            RestangularAppService.all('boards').one('subscribe', $scope.thread.id).customPUT(payload).then(function () {
                $scope.getData();
            });
        };
    });