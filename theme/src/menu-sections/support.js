'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider
            .state('support', {
                parent: 'menu-section',
                url: '/support',
                menu: {
                    name: 'Support',
                    class: 'fa fa-support',
                    tag: 'sidebar',
                    priority: 50
                }
            })
    });