'use strict';

angular.module('biffyApp')
    .controller('ProjectsViewController', function ($scope, RestangularAppService, $stateParams, UserService, $location, $modal, NotifierService, BreadcrumbService) {
        $scope.project = {};
        $scope.id = $stateParams.projectId;
        $scope.currentUser = UserService.getUser();
        $scope.search = $location.search();

        BreadcrumbService.add({text: 'Projects', state: 'projects'});

        $scope.getData = function () {
            RestangularAppService.one('projects/project', $scope.id).get().then(function (data) {
                $scope.project = data;

                _.each($scope.project.tasks, function (task) {
                    if (task.id === parseInt($scope.search.task)) {
                        task.isOpen = true;
                    }

                    $scope.$watch(function () {
                        return task.isOpen;
                    }, function (newV) {
                        if (newV) {
                            $location.search('task', task.id);
                            $location.search('subtask', null);
                        }
                    });

                    _.each(task.subtasks, function (subtask) {
                        if (subtask.id === parseInt($scope.search.subtask)) {
                            subtask.isOpen = true;
                        }

                        $scope.$watch(function () {
                            return subtask.isOpen;
                        }, function (newV) {
                            if (newV) {
                                $location.search('subtask', subtask.id);
                            }
                        });
                    });
                });
            });
        };

        $scope.getData();

        $scope.completeTask = function (taskId, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            RestangularAppService.all('projects').one('task', taskId).customPUT({complete: true}).then(function () {
                $scope.getData();
            });
        };

        $scope.addComment = function (taskId) {
            var modalInstance = $modal.open({
                templateUrl: 'src/projects/comment/comment.modal.html',
                controller: 'ProjectsCommentModalController',
                size: 'lg',
                resolve: {
                    taskId: function () {
                        return taskId;
                    }
                }
            });

            modalInstance.result.then(function () {
                $scope.getData();
            });
        };

        $scope.addTask = function () {
            var modalInstance = $modal.open({
                templateUrl: 'src/projects/task/task.modal.html',
                controller: 'ProjectsTaskModalController',
                size: 'lg',
                resolve: {
                    taskData: function () {
                        return {
                            projectId: $scope.project.id,
                            taskId: 'new'
                        };
                    }
                }
            });

            modalInstance.result.then(function () {
                $scope.getData();
            });
        };

        $scope.addSubtask = function (taskId) {
            var modalInstance = $modal.open({
                templateUrl: 'src/projects/task/task.modal.html',
                controller: 'ProjectsTaskModalController',
                size: 'lg',
                resolve: {
                    taskData: function () {
                        return {
                            taskId: taskId,
                            subtaskId: 'new'
                        };
                    }
                }
            });

            modalInstance.result.then(function () {
                $scope.getData();
            });
        };

        $scope.completeProject = function () {
            RestangularAppService.one('projects/project', $scope.id).customPUT({complete: true}).then(function () {
                NotifierService.success('Project completed!');
            });
        };

        $scope.makeTemplate = function () {
            var modalInstance = $modal.open({
                templateUrl: 'src/projects/template/template.modal.html',
                controller: 'ProjectsTemplateModalController',
                size: 'lg',
                resolve: {
                    projectId: function () {
                        return $scope.project.id;
                    }
                }
            });

            modalInstance.result.then(function () {
                NotifierService.success('Project template created.');
            });
        };

        $scope.refresh = function (search) {
            if (search === '') {
                return;
            }

            RestangularAppService.all('users').getList({'username-list': '', 'search': search}).then(function (data) {
                $scope.users = data;
            })
        };
    });