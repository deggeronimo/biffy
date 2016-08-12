'use strict';

/* jshint undef: false */
angular.module('biffy.auth')
    .service('AuthService', function ($auth, RestangularAuthService, $rootScope, $timeout, NotifierService, $state) {
        var self = this;
        var pinTimeout = null;
        var pinTimeoutDelay = 5 * 60 * 1000; // in milliseconds (# mins * 60 * 1000)

        this.listenLogin = function (callback) {
            $rootScope.$on('login', callback);
        };

        this.listenLogout = function (callback) {
            $rootScope.$on('logout', callback);
        };

        this.listenPinLogin = function (callback) {
            $rootScope.$on('pinLogin', callback);
        };

        this.listenPinLogout = function (callback) {
            $rootScope.$on('pinLogout', callback);
        };

        this.listenAuthFailed = function (callback) {
            $rootScope.$on('authFailed', callback);
        };

        this.authLink = function () {
            $auth.link('google').then(function () {
                self.handleAuth(true);
            }, function (response) {
                if (response.status) {
                    switch (response.status) {
                        case 403:
                            NotifierService.error(response.data.error);
                            break;
                        case 500: // error
                        case 503: // maintenance
                            // todo handle error
                            break;
                    }
                } else {
                    $rootScope.$broadcast('authFailed');
                }
            });
        };

        this.handleAuth = function (authLinkAttempted) {
            RestangularAuthService.all('me').customGET().then(
                function (data) {
                    $rootScope.$broadcast('login', data);
                },
                function () {
                    if (isDefined(authLinkAttempted) && authLinkAttempted) {
                        return false;
                    }
                    self.authLink();
                }
            );
        };

        this.loginButton = function () {
            self.authLink();
        };

        this.pinLoggedIn = function () {
            return pinTimeout !== null;
        };

        this.checkPin = function () {
            var pin = window.prompt('Please enter your PIN');
            if (pin) {
                return RestangularAuthService.all('pin').customPOST({pin: pin});
            }
        };

        this.startPinTimeout = function (user) {
            $rootScope.$broadcast('pinLogin', user);
            pinTimeout = $timeout(self.handlePinTimeout, pinTimeoutDelay);
        };

        this.handlePinTimeout = function (){
            $rootScope.$broadcast('pinLogout');
            $timeout.cancel(pinTimeout);
            pinTimeout = null;
            $state.go('home');
            return RestangularAuthService.all('pin').customDELETE();
        };

        this.pinLogout = function () {
            self.handlePinTimeout();
        };

        this.changeStore = function (id) {
            return RestangularAuthService.all('store').customPOST({store_id: id});
        };

        this.logout = function () {
            RestangularAuthService.all('me').remove();
            $rootScope.$broadcast('logout');
        };

        this.pinPermissions = function (pin) {
            return RestangularAuthService.all('pin-permissions').customPOST({pin: pin});
        };
    });