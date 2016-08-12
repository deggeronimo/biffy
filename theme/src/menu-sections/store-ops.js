'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider
            .state('store-ops', {
                parent: 'menu-section',
                url: '/store-ops',
                menu: {
                    name: 'Store Operations',
                    class: 'fa fa-briefcase',
                    tag: 'sidebar',
                    priority: 125
                }
            });
    });