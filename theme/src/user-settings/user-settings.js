'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('user-settings', {
            parent: 'authorized',
            url: '/user-settings',
            views: {
                '@': {
                    templateUrl: 'src/user-settings/user-settings.html',
                    controller: 'UserSettingsController'
                }
            }
        });
    });