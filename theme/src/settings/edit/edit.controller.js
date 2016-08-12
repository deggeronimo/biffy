'use strict';

angular.module('biffyApp')
    .controller('SettingsEditController', function ($scope, RestangularAppService, BreadcrumbService, $stateParams, $state, NotifierService) {
        $scope.setting = {};

        BreadcrumbService.add({text: 'Settings', state: 'settings'});

        $scope.isAdd = function () {
            return $stateParams.id === 'new';
        };

        $scope.mode = $scope.isAdd() ? 'Add' : 'Edit';

        if (!$scope.isAdd()) {
            RestangularAppService.one('settings', $stateParams.id).get().then(function (data) {
                $scope.setting = data;
            });
        }

        $scope.save = function () {
            if ($scope.isAdd()) {
                NotifierService.info('Creating setting for all users, this may take a moment.');
                RestangularAppService.all('settings').post($scope.setting).then(function () {
                    $state.transitionTo('settings');
                });
            } else {
                $scope.setting.put().then(function () {
                    $state.transitionTo('settings');
                });
            }
        };

        $scope.cancel = function () {
            $state.transitionTo('settings');
        };
    });