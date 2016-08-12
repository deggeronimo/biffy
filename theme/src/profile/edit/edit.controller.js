'use strict';

angular.module('biffyApp')
    .controller('ProfileEditController', function ($scope, RestangularAppService, BreadcrumbService, $state, $stateParams) {
        $scope.user = {};

        BreadcrumbService.add({text: 'Profile'});

        // todo verify can edit profile for selected user (admin + other | self)

        RestangularAppService.one('users', $stateParams.userId).get({profile: true}).then(function (user) {
            $scope.user = user;
        });

        $scope.save = function () {
            $scope.user.put().then(function () {
                $state.transitionTo('profile', {userId: $scope.user.id});
            });
        };

        $scope.cancel = function () {
            $state.transitionTo('profile', {userId: $scope.user.id});
        };
    });