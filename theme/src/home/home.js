'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider
            .state('home', {
                parent: 'userAuthorized',
                url: '/',
                views: {
                    '@': {
                        templateUrl: 'src/home/home.html'
                    }
                },
                menu: {
                    name: 'Dashboard',
                    class: 'fa fa-home',
                    tag: 'sidebar',
                    priority: 200
                }
            })
        ;
    });
