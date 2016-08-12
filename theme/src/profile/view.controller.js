'use strict';

angular.module('biffyApp')
    .controller('ProfileViewController', function ($scope, RestangularAppService, $stateParams, BreadcrumbService) {
        $scope.user = {};

        BreadcrumbService.add({text: 'Profile'});

        $scope.getData = function () {
            RestangularAppService.one('users', $stateParams.userId).get({profile: true, boards: true}).then(function (data) {
                $scope.user = data;
            });
        };

        $scope.getData();
    });