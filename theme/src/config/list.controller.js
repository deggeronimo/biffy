'use strict';

angular.module('biffyApp')
    .controller('ConfigListController', function ($scope, RestangularAppService) {
        $scope.config = {};

        $scope.getData = function () {
            RestangularAppService.all('config').getList().then(function (data) {
                $scope.config = data;
            });
        };

        $scope.getData();

        $scope.destroy = function (configId) {
            RestangularAppService.one('config', configId).remove().then(function () {
                $scope.getData();
            });
        };
    });