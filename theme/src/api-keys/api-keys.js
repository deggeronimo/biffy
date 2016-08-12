'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('api-keys', {
            parent: 'data-management',
            url: '/api-keys',
            views: {
                '@': {
                    templateUrl: 'src/api-keys/api-keys.html',
                    controller: 'ApiKeysController'
                }
            },
            menu: {
                name: 'API Keys',
                class: 'fa fa-key',
                tag: 'sidebar',
                priority: 100
            }
        });
    });