'use strict';

angular.module('biffyApp')
    .controller('PermissionsEditController', function($q, $scope, $stateParams, $state, RestangularAppService, NotifierService) {

        $scope.id = $stateParams.id;
        $scope.data = {global: false, description: ''};
        $scope.mode = 'Loading';

        $scope.isAdd = function() {
            return $scope.id === 'new';
        };

        $scope.isEdit = function() {
            return $scope.mode === 'Edit';
        };

        if($scope.isAdd()) {
            $scope.mode = 'Add';
        } else {
            RestangularAppService.one('permissions', $scope.id).get().then(
                function(data) {
                    $scope.mode = 'Edit';
                    $scope.data = data;
                },
                function(data) {
                    NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
                }
            );
        }

        $scope.store = function() {
            RestangularAppService.all('permissions').post($scope.data).then(function() {
                $state.transitionTo('permissions');
            }, function(data) {
                NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
            });

        };

        $scope.update = function() {
            $scope.data.put().then(function() {
                $state.transitionTo('permissions');
            }, function(data) {
                NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
            });
        };

        $scope.destroy = function() {
            $scope.data.remove().then(function() {
                $state.transitionTo('permissions');
            }, function(data) {
                NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
            });
        };

        $scope.cancel = function() {
            $state.transitionTo('permissions');
        };

    });