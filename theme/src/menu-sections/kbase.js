'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider
            .state('kbase', {
                parent: 'menu-section',
                url: '/kbase',
                menu: {
                    name: 'Knowledge Base',
                    class: 'fa fa-book',
                    tag: 'sidebar',
                    priority: 75
                }
            });
    });