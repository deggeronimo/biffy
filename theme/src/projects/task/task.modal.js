'use strict';

angular.module('biffyApp')
    .controller('ProjectsTaskModalController', function ($scope, RestangularAppService, $modalInstance, taskData) {
        $scope.task = {};
        $scope.hasParent = false;

        if (taskData.subtaskId) {
            $scope.id = taskData.subtaskId;
            $scope.hasParent = true;
        } else {
            $scope.id = taskData.taskId;
        }

        $scope.isAdd = function () {
            return $scope.id === 'new';
        };

        $scope.mode = $scope.isAdd() ? 'Add' : 'Edit';

        if (!$scope.isAdd()) {
            RestangularAppService.all('projects').one('task', $scope.id).get().then(function (data) {
                $scope.task = data;
            });
        } else {
            if ($scope.hasParent) {
                $scope.task.projectId = null;
                $scope.task.parent = taskData.taskId;
            } else {
                $scope.task.projectId = taskData.projectId;
                $scope.task.parent = null;
            }
        }

        $scope.save = function () {
            if ($scope.isAdd()) {
                RestangularAppService.all('projects/task').post($scope.task).then(function () {
                    $modalInstance.close();
                });
            } else {
                $scope.task.put().then(function () {
                    $modalInstance.close();
                })
            }
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };
    });