'use strict';

angular.module('biffyApp')
    .controller('SettingsController', function ($scope, RestangularAppService, BreadcrumbService) {
        $scope.settings = [];

        BreadcrumbService.add({text: 'Settings'});

        RestangularAppService.all('settings').getList().then(function (data) {
            $scope.settings = data;
        });
    });