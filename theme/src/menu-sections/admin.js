'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider
            .state('admin', {
                parent: 'menu-section',
                url: '/admin',
                menu: {
                    name: 'Administration',
                    class: 'fa fa-empire',
                    tag: 'sidebar',
                    priority: 10
                }
            });
    });