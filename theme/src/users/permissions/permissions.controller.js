'use strict';

angular.module('biffyApp')
    .controller('UsersPermissionsController', function($q, $scope, $stateParams, $state, RestangularAppService, NotifierService) {

        $scope.id = $stateParams.id;
        $scope.userPermissions = {};

        $q.all([
            RestangularAppService.all('permissions').getList({global:1}),
            RestangularAppService.one('users', $scope.id).get(),
            RestangularAppService.one('users', $scope.id).getList('permissions')
        ]).then(function(result) {
            $scope.permissions = result[0];
            $scope.user = result[1];
            $scope.userPermissions = result[2];
        }, function(data) {
            NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
        });

        $scope.togglePermission = function(id) {
            var index = $scope.userPermissions.indexOf(id);
            if (index > -1) {
                $scope.userPermissions.splice(index, 1);
            } else {
                $scope.userPermissions.push(id);
            }
        };

        $scope.update = function() {
            RestangularAppService.one('users', $scope.id).post('permissions', {permissions: $scope.userPermissions}).then(function() {
                $state.transitionTo('users');
            }, function(data) {
                NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
            });
        };

        $scope.cancel = function() {
            $state.transitionTo('users');
        };

    });