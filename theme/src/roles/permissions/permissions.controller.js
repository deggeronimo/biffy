'use strict';

angular.module('biffyApp')
    .controller('RolesPermissionsController', function($q, $scope, $stateParams, $state, RestangularAppService, NotifierService) {

        $scope.id = $stateParams.id;
        $scope.role = {};

        $q.all([
            RestangularAppService.all('permissions').getList({global: 0}),
            RestangularAppService.one('roles', $scope.id).get()
        ]).then(function(result) {
            $scope.permissions = result[0];
            $scope.role = result[1];
        }, function(data) {
            NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
        });

        $scope.togglePermission = function(id) {
            var index = $scope.role.permissions.indexOf(id);
            if (index > -1) {
                $scope.role.permissions.splice(index, 1);
            } else {
                $scope.role.permissions.push(id);
            }
        };

        $scope.update = function() {
            $scope.role.put().then(function() {
                $state.transitionTo('roles');
            }, function(data) {
                NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
            });
        };

        $scope.cancel = function() {
            $state.transitionTo('roles');
        };

    });