'use strict';

angular.module('biffyApp')
    .controller('ApiKeysController', function ($scope, RestangularAppService) {
        $scope.data = {};

        $scope.getData = function () {
            RestangularAppService.all('key/keys').getList().then(function (data) {
                $scope.data = data;
            });
        };

        $scope.addKey = function () {
            var name = window.prompt('Please enter a name for the key');
            RestangularAppService.all('key/generate').post({name: name}).then(function (data) {
                window.prompt('Here is the API key:', data.key);
                $scope.getData();
            });
        };

        $scope.deleteKey = function (key) {
            RestangularAppService.one('key/key', key.id).remove().then(function () {
                $scope.getData();
            });
        };

        $scope.getData();
    });