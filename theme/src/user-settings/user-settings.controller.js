'use strict';

angular.module('biffyApp')
    .controller('UserSettingsController', function ($scope, RestangularAppService, BreadcrumbService, UserService, NotifierService) {
        var user = UserService.getUser();
        $scope.settings = [];
        $scope.userSettings = user.settings;

        BreadcrumbService.add({text: 'Settings'});

        RestangularAppService.all('settings').getList().then(function (data) {
            $scope.settings = data;
        });

        $scope.save = function () {
            RestangularAppService.one('users', user.id).customPUT({settings: $scope.userSettings}).then(function () {
                NotifierService.success('Settings updated');
            })
        };

        $scope.getUserSetting = function (id) {
            var val = _.findWhere($scope.userSettings, {setting_id: id});
            if (parseInt(val.value) == val.value) {
                val.value = parseInt(val.value);
            }
            return val;
        };
    });