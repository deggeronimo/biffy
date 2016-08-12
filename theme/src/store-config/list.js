'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('store-config', {
            parent: 'store-ops',
            url: '/store-config',
            views: {
                '@': {
                    templateUrl: 'src/store-config/list.html',
                    controller: 'StoreConfigListController'
                }
            },
            menu: {
                name: 'Store Config',
                class: 'fa fa-cogs',
                tag: 'sidebar',
                priority: 50
            }
        });
    });