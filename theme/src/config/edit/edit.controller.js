'use strict';

angular.module('biffyApp')
    .controller('ConfigEditController', function ($scope, RestangularAppService, $stateParams, $state, NotifierService) {
        $scope.config = {};
        $scope.mode = 'Loading';
        $scope.id = $stateParams.id;

        $scope.isAdd = function () {
            return $scope.id === 'new';
        };

        if ($scope.isAdd()) {
            $scope.mode = 'Add';
        } else {
            RestangularAppService.one('config', $scope.id).get().then(function (data) {
                $scope.mode = 'Edit';
                $scope.config = data;
            });
        }

        $scope.create = function () {
            NotifierService.info('Creating config for all stores, please wait one moment.');
            RestangularAppService.all('config').post($scope.config).then(function () {
                $state.transitionTo('config');
            });
        };

        $scope.update = function () {
            $scope.config.put().then(function () {
                $state.transitionTo('config');
            });
        };

        $scope.save = function () {
            if ($scope.isAdd()) {
                $scope.create();
            } else {
                $scope.update();
            }
        };
    });