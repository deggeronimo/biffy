'use strict';

angular.module('biffy.user')
    .service('UserService', function (AuthService, $q, $rootScope) {
        var _user = undefined;
        var _pinUser = undefined;
        var self = this;

        AuthService.listenLogin(function (event, user) {
            _user = user;
            $rootScope.$broadcast('updateUser', _user);
        });

        AuthService.listenLogout(function () {
            _user = undefined;
            $rootScope.$broadcast('updateUser', _user);
        });

        AuthService.listenPinLogin(function (event, pinUser) {
            _pinUser = pinUser;
            $rootScope.$broadcast('updatePinUser', _pinUser);
        });

        AuthService.listenPinLogout(function () {
            _pinUser = undefined;
            $rootScope.$broadcast('updatePinUser', _pinUser);
        });

        this.listenUpdateUser = function (callback) {
            $rootScope.$on('updateUser', callback);
        };

        this.listenUpdatePinUser = function (callback) {
            $rootScope.$on('updatePinUser', callback);
        };

        this.updateUser = function (user) {
            _user = user;
            $rootScope.$broadcast('updateUser', _user);
        };

        this.getStores = function () {
            return _user.stores;
        };

        this.getUser = function () {
            if (_user.needs_pin && isDefined(_pinUser)) {
                return _pinUser;
            }

            return _user;
        };

        this.user = function () {
            var deferred = $q.defer();
            if (isUndefined(_user)) {
                AuthService.listenLogin(function (event, user) {
                    deferred.resolve(user);
                });
            } else {
                deferred.resolve(_user);
            }
            return deferred.promise;
        };

        this.pinUser = function () {
            var deferred = $q.defer();
            if (typeof _pinUser === 'undefined') {
                AuthService.listenPinLogin(function (event, pinUser) {
                    deferred.resolve(pinUser);
                });
            } else {
                deferred.resolve(_pinUser);
            }
            return deferred.promise;
        };

        this.currentUser = function () {
            var deferred = $q.defer();
            self.user().then(function (user) {
                if (user.needs_pin) {
                    self.pinUser().then(function (pinUser) {
                        deferred.resolve(pinUser);
                    });
                } else {
                    deferred.resolve(user);
                }
            });
            return deferred.promise;
        };

        this.userNeedsPin = function () {
            var deferred = $q.defer();
            self.user().then(function (user) {
                if (user.needs_pin && !AuthService.pinLoggedIn()) {
                    deferred.resolve();
                } else {
                    deferred.reject();
                }
            });
            return deferred.promise;
        };

        var checkPermission = function (permission, user) {
            if (user.admin) {
                return true;
            }

            if (permission in user.permissions) {
                return user.permissions[permission];
            } else {
                return true; // todo log this or otherwise report?
            }
        };

        this.hasPermission = function (permission) {
            return checkPermission(permission, self.getUser());
        };

        this.userCan = function (permission) {
            return self.hasPermission(permission);
        };

        this.promiseUserCan = function (permission) {
            var deferred = $q.defer();
            self.currentUser().then(function (user) {
                if (checkPermission(permission, user)) {
                    deferred.resolve();
                } else {
                    deferred.reject();
                }
            });
            return deferred.promise;
        };

        this.verifyManagerPin = function () {
            var deferred = $q.defer();
            if (self.getUser().admin) {
                deferred.resolve();
            } else {
                var pin = prompt('This action requires manager approval, please enter manager PIN');
                if (pin === null) {
                    deferred.reject();
                } else {
                    AuthService.pinPermissions(pin).then(function (user) {
                        // todo figure out permission for this
                        if (checkPermission('pos-manager', user)) {
                            deferred.resolve();
                        } else {
                            deferred.reject();
                        }
                    }, function () {
                        deferred.reject();
                    });
                }
            }
            return deferred.promise;
        };
	});