'use strict';
/* jshint undef: false */
angular.module('biffy.store')
    .factory('StoreService', function ($rootScope, RestangularAppService, AuthService, UserService, NotifierService) {
        var current = {
            id: undefined,
            name: 'Select Store',
            config: {}
        };
        var userStoreList = [];
        return {
            stores: function () {
                return userStoreList;
            },
            current: current,
            id: function () {
                return current.id;
            },
            name: function () {
                return current.name;
            },
            config: function (key) {
                return _.findWhere(current.config, {key: key}).value;
            },
            resolve: function (user) {
                userStoreList = user.stores;
                var selectedStore = user.currentStore;
                current.id = selectedStore.id;
                current.name = selectedStore.name;
                current.config = selectedStore.config;
                return userStoreList;
            },
            clear: function () {
                current.id = undefined;
                current.name = 'Select Store';
            },
            change: function (id) {
                AuthService.changeStore(id).then(function (user) {
                    UserService.updateUser(user);
                    var selectedStore = user.currentStore;
                    current.id = selectedStore.id;
                    current.name = selectedStore.name;
                    current.config = selectedStore.config;
                    $rootScope.$broadcast('store.select'); // todo remove listeners for this event
                    $rootScope.reload();
                }, function(response){
                    if (response.status === 403) {
                        NotifierService.error('You do not have access to that store.');
                    }
                });
            }
        };
    });
