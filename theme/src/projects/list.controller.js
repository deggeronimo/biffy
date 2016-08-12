'use strict';

angular.module('biffyApp')
    .controller('ProjectsListController', function ($scope, RestangularAppService, BreadcrumbService) {
        $scope.projects = [];
        $scope.viewCompleted = false;

        BreadcrumbService.add({text: 'Projects'});

        $scope.getData = function () {
            RestangularAppService.all('projects').getList({completed: $scope.viewCompleted}).then(function (data) {
                $scope.projects = data;
            });
        };

        $scope.getData();

        $scope.setViewCompleted = function (val) {
            $scope.viewCompleted = val;
            $scope.getData();
        };
    });