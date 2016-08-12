'use strict';

angular.module('biffyApp')
    .controller('ProjectsTemplateModalController', function ($scope, RestangularAppService, $modalInstance, projectId) {
        $scope.template = {projectId: projectId};

        $scope.save = function () {
            RestangularAppService.all('projects/template').post($scope.template).then(function () {
                $modalInstance.close();
            });
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };
    });