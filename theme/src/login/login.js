'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('login', {
            url: '/login',
            views: {
                '@': {
                    templateUrl: 'src/login/login.html',
                    controller: 'LoginController'
                }
            }
        });
    });