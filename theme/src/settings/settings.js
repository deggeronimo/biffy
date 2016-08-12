'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('settings', {
            parent: 'admin',
            url: '/settings',
            views: {
                '@': {
                    templateUrl: 'src/settings/settings.html',
                    controller: 'SettingsController'
                }
            },
            menu: {
                name: 'Settings',
                class: 'fa fa-cogs',
                tag: 'sidebar',
                priority: 100
            }
        });
    });