'use strict';

angular.module('biffyApp')
    .controller('LoginController', function ($scope, AuthService, NotifierService, $state) {
        AuthService.listenAuthFailed(function () {
            NotifierService.error('Please allow popups if you are unable to login.');
        });

        AuthService.listenLogin(function () {
            $state.transitionTo('home');
        });

        $scope.login = function () {
            AuthService.loginButton();
        };
    });