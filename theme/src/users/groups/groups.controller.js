'use strict';

angular.module('biffyApp')
    .controller('UsersGroupsController', function($q, $scope, $stateParams, $state, RestangularAppService, NotifierService) {

        $scope.id = $stateParams.id;
        $scope.data = {};

        $q.all([
            RestangularAppService.all('groups').getList({noPagination: true}),
            RestangularAppService.one('users', $scope.id).get({groups: true})
        ]).then(function(result) {
            $scope.groups = result[0];
            $scope.user = result[1];

        }, function(data) {
            NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
        });

        $scope.toggleGroup = function(id) {
            var index = $scope.user.groups.indexOf(id);
            if (index > -1) {
                $scope.user.groups.splice(index, 1);
            } else {
                $scope.user.groups.push(id);
            }
        };

        $scope.update = function() {
            $scope.user.put().then(function() {
                $state.transitionTo('users');
            }, function(data) {
                NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
            });
        };

        $scope.cancel = function() {
            $state.transitionTo('users');
        };

    });