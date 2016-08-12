'use strict';

angular.module('biffyApp')
    .controller('ProjectsCommentModalController', function ($scope, RestangularAppService, $modalInstance, taskId) {
        $scope.comment = {task_id: taskId};

        $scope.save = function () {
            RestangularAppService.all('projects').customPOST($scope.comment, 'comment').then(function () {
                $modalInstance.close();
            });
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };
    });