'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('config', {
            parent: 'admin',
            url: '/config',
            views: {
                '@': {
                    templateUrl: 'src/config/list.html',
                    controller: 'ConfigListController'
                }
            },
            menu: {
                name: 'Config',
                class: 'fa fa-cogs',
                tag: 'sidebar',
                priority: 50
            }
        })
    });