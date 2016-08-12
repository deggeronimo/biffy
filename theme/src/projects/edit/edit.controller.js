'use strict';

angular.module('biffyApp')
    .controller('ProjectsEditController', function ($scope, RestangularAppService, $stateParams, $state, UserService, BreadcrumbService) {
        $scope.project = {};
        $scope.id = $stateParams.projectId;
        $scope.users = [];
        $scope.templates = [];
        $scope.currentUser = UserService.getUser();

        $scope.isAdd = function () {
            return $scope.id === 'new';
        };

        if ($scope.isAdd()) {
            $scope.project.users = [
                {
                    id: $scope.currentUser.id,
                    username: $scope.currentUser.username,
                    cannotRemove: true
                }
            ];
        }

        BreadcrumbService.add({text: 'Projects', state: 'projects'});

        $scope.mode = $scope.isAdd() ? 'Add' : 'Edit';

        if (!$scope.isAdd()) {
            RestangularAppService.one('projects', $scope.id).get().then(function (data) {
                $scope.project = data;
            });
        } else {
            RestangularAppService.all('projects/templates').getList().then(function (data) {
                $scope.templates = data;
            });
        }

        $scope.update = function () {
            $scope.project.put().then(function () {
                $state.transitionTo('projects.view', {projectId: $scope.id});
            });
        };

        $scope.store = function () {
            RestangularAppService.all('projects').post($scope.project).then(function (data) {
                $state.transitionTo('projects.view', {projectId: data.id});
            });
        };

        $scope.cancel = function () {
            if ($scope.isAdd()) {
                $state.transitionTo('projects');
            } else {
                $state.transitionTo('projects.view', {projectId: $scope.id});
            }
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