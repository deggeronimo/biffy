'use strict';

angular.module('biffyApp')
    .controller('CustomersEditController', function ($q, $scope, $stateParams, $state, RestangularAppService, NotifierService) {

        $scope.id = $stateParams.id || null;
        $scope.data = {};
        $scope.mode = 'Loading';

        $scope.isAdd = function () {
            return $scope.id === null;
        };

        $scope.isEdit = function () {
            return $scope.mode === 'Edit';
        };

        $q.all([
            RestangularAppService.all('stores').getList()
        ]).then(function (result) {
            $scope.stores = result[0];
            if ($scope.isAdd()) {
                $scope.mode = 'Add';
            } else {
                RestangularAppService.one('customers', $scope.id).get().then(
                    function (data) {
                        $scope.mode = 'Edit';
                        $scope.data = data;
                    },
                    function (data) {
                        NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
                    }
                );
            }
        });

        $scope.store = function () {
            RestangularAppService.all('customers').post($scope.data).then(function () {
                $state.transitionTo('customers');
            }, function (data) {
                NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
            });

        };

        $scope.update = function () {
            $scope.data.put().then(function () {
                $state.transitionTo('customers');
            }, function (data) {
                NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
            });
        };

        $scope.destroy = function () {
            $scope.data.remove().then(function () {
                $state.transitionTo('customers');
            }, function (data) {
                NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
            });
        };

        $scope.cancel = function () {
            $state.transitionTo('customers');
        };

    });