'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider
            .state('data-management', {
                parent: 'menu-section',
                url: '/data-management',
                menu: {
                    name: 'Data Management',
                    class: 'fa fa-database',
                    tag: 'sidebar',
                    priority: 0
                }
            })
    });