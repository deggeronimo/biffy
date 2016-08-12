'use strict';

angular.module('biffyApp')
    .controller('EmployeesRolesController', function($q, $scope, $stateParams, $state, RestangularAppService, NotifierService) {

        $scope.id = $stateParams.id;
        $scope.data = {};
        $scope.role = 0;
        $scope.saveDisabled = true;

        $q.all([
            RestangularAppService.all('roles').getList(),
            RestangularAppService.one('users', $scope.id).get()
        ]).then(function(result) {
            $scope.roles = result[0];
            $scope.data = result[1];
        }, function (data) {
            NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
        });

        $scope.selectRole = function(id) {
            $scope.role = id;
            $scope.saveDisabled = false;
        };

        $scope.update = function() {
            RestangularAppService.one('users', $scope.id).one('roles', $scope.role).put().then(function() {
                $state.transitionTo('employees');
            }, function (data) {
                NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
            });
        };

        $scope.cancel = function() {
            $state.transitionTo('employees');
        };

    });