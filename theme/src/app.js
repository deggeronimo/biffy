'use strict';

angular
    .module('biffyApp', [
        'ui.bootstrap',
        'theme',
        'ngCookies',
        'ngSanitize',
        'ngAnimate',
        'ui.router',
        'restangular',
        'ngTable',
        'ui.router.menus',
        'satellizer',
        'ui.date',
        'ui.select',
        'dialogs.main',
        'dialogs.default-translations',
        'ui.tree',
        'angularFileUpload',
        'ui.calendar',
        'ui.utils',
        'LocalStorageModule',
        'textAngular',
        'ui.bootstrap.datetimepicker',
        'vxWamp',
        'jlareau.pnotify',
        'biffy'
    ])
    .config(function ($locationProvider, $urlRouterProvider, $authProvider, $stateProvider, localStorageServiceProvider, $wampProvider) {
        $locationProvider.html5Mode(true);
        $urlRouterProvider.otherwise('/');

        $authProvider.google({
            clientId: '14503069487-rfeu108r6d1kc8bjc6tq3veupkdkphd2.apps.googleusercontent.com',
            redirectUri: 'http://portal.ubif.net/',
            url: '/api/auth',
            requiredUrlParams: ['scope', 'access_type'],
            accessType: 'offline'
        });

        $stateProvider
            .state('userAuthorized', {
                resolve: {
                    userAuthorized: function ($q, UserService, StoreService) {
                        var deferred = $q.defer();
                        UserService.user().then(function () {
                            StoreService.resolve(UserService.getUser());
                            deferred.resolve();
                        });
                        return deferred.promise;
                    }
                }
            })
            .state('authorized', {
                parent: 'userAuthorized',
                resolve: {
                    // todo verify pin required after initial pin timeout
                    pinAuthorized: function ($q, UserService, AuthService, NotifierService) {
                        var deferred = $q.defer();
                        UserService.userNeedsPin().then(function () {
                            var checkPinPromise = AuthService.checkPin();
                            if (isDefined(checkPinPromise)) {
                                checkPinPromise.then(function (user) {
                                    AuthService.startPinTimeout(user);
                                    deferred.resolve();
                                }, function () {
                                    NotifierService.error('You did not enter a valid PIN.');
                                    deferred.reject();
                                });
                            } else {
                                NotifierService.error('Please enter a PIN or login with your account for access.');
                                deferred.reject();
                            }
                        }, function () {
                            deferred.resolve();
                        });
                        return deferred.promise;
                    }
                }
            })
        ;

        localStorageServiceProvider.setPrefix('biffy');

        $wampProvider.init({
            url: 'ws://portal.ubif.net:8080',
            realm: 'realm1'
        });
    })
    .run(function ($rootScope, $state, $stateParams, AuthService, UserService, NotifierService, authLoginPage) {
        $rootScope.reload = function () {
            $state.transitionTo($state.current, $stateParams, {
                reload: true,
                inherit: false,
                notify: true
            });
        };

        if (!authLoginPage) {
            AuthService.handleAuth(true);
        }

        $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
            UserService.promiseUserCan(toState.name).catch(function () {
                NotifierService.error('You do not have access to that area.');
                $state.go('home');
            });
        });

        // todo 401 response interceptor
    })
;
