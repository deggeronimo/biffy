'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('settings.edit', {
            url: '/edit/{id}',
            views: {
                '@': {
                    templateUrl: 'src/settings/edit/edit.html',
                    controller: 'SettingsEditController'
                }
            }
        });
    });