angular.module('theme')
    .controller('ThemeController', function ($scope, $global, $timeout, $interval, Restangular, $http, notificationService, progressLoader, $state, AuthService, UserService, $rootScope, localStorageService, authLoginPage) {
        if (($scope.styleOpts = localStorageService.get('styleOpts')) === null) {
            $scope.styleOpts = {
                fixedHeader: $global.get('fixedHeader'),
                headerBarHidden: $global.get('headerBarHidden'),
                fullscreen: $global.get('fullscreen'),
                leftbarCollapsed: $global.get('leftbarCollapsed'),
                leftbarShown: $global.get('leftbarShown'),
                rightbarCollapsed: $global.get('rightbarCollapsed'),
                isSmallScreen: false,
                showSearchCollapsed: $global.get('showSearchCollapsed'),
                setMainBG: $global.get('setMainBG')
            };
        }
        $scope.$on('globalStyles:changed', function (event, newVal) {
            $scope.styleOpts[newVal.key] = newVal.value;
            localStorageService.set('styleOpts', $scope.styleOpts);
        });

        $global.set('fullscreen', authLoginPage);

        AuthService.listenLogin(function () {
            $global.set('fullscreen', false);
        });

        AuthService.listenLogout(function () {
            $global.set('fullscreen', true);
        });

        if (authLoginPage) {
            $timeout(function () {
                $state.go('login');
            }, 1);
        }

        $scope.logout = function () {
            AuthService.logout();
            $state.go('login');
        };

        $scope.pinLogout = function () {
            AuthService.pinLogout();
        };

        $scope.updateUser = function (event, user) {
            $scope.user = user;
        };

        $scope.updatePinUser = function (event, pinUser) {
            $scope.pin_user = pinUser;
        };

        UserService.listenUpdateUser($scope.updateUser);
        UserService.listenUpdatePinUser($scope.updatePinUser);

        $scope.hideSearchBar = function () {
            $global.set('showSearchCollapsed', false);
        };

        $scope.hideHeaderBar = function () {
            $global.set('headerBarHidden', true);
        };

        $scope.showHeaderBar = function ($event) {
            $event.stopPropagation();
            $global.set('headerBarHidden', false);
        };

        $scope.toggleLeftBar = function () {
            if ($scope.styleOpts.isSmallScreen) {
                return $global.set('leftbarShown', !$scope.styleOpts.leftbarShown);
            }
            $global.set('leftbarCollapsed', !$scope.styleOpts.leftbarCollapsed);
        };

        $scope.toggleRightBar = function () {
            if ($scope.incoming_conn == null) {
                $scope.call.incoming = false;
            }
            $global.set('rightbarCollapsed', !$scope.styleOpts.rightbarCollapsed);
        };

        $scope.$on('globalStyles:maxWidth767', function (event, newVal) {
            $timeout(function () {
                $scope.styleOpts.isSmallScreen = newVal;
                if (!newVal) {
                    $global.set('leftbarShown', false);
                } else {
                    $global.set('leftbarCollapsed', false);
                }
            });
        });

        $rootScope.$on('$stateChangeStart', function () {
            $global.set('setMainBG', false);
        });

        $scope.rightbarAccordionsShowOne = false;
        $scope.rightbarAccordions = [{open: true}, {open: true}, {open: true}, {open: true}, {open: true}, {open: true}, {open: true}];

		$scope.userCan = UserService.userCan;

        $scope.openNavItem = function (menu, menus) {
            forEach(menus, function (item) {
                item.open = false;
            });
            menu.open = true;
        };

        $scope.openNavChild = function (child, menu, menus) {
            $scope.openNavItem(menu, menus);
            forEach(menu.children, function (item) {
                item.open = false;
            });
            child.open = true;
        };

        // Initiate call
        $scope.call = new Object;
        $scope.call.timeCount = 0;
        $scope.call.hhours = "00";
        $scope.call.mminutes = "00";
        $scope.call.sseconds = "00";
        $scope.call.incoming = false;
        $scope.call.connecting = false;
        $scope.incoming_conn = null;
        $scope.promise = null;
        $scope.token = "";

        //Get Twilio token
        Restangular.all('api/twilio/token').post().then(function(data) {
            $scope.token = data.token;
            Twilio.Device.setup(data.token);
            Twilio.Device.ready(function (device) {

            });
            Twilio.Device.error(function (error) {
                // alert("Error: " + error.message);
            });
            Twilio.Device.offline(function() {
                Restangular.all('api/twilio/token').post().then(function(data) {
                    Twilio.Device.setup(data.token);
                });
            });
            Twilio.Device.incoming(function (conn) {
                $scope.call.from_number = conn.parameters.From;
                $scope.call.to_number = conn.parameters.To;

                if (!$scope.styleOpts.rightbarCollapsed)
                    $global.set('rightbarCollapsed', !$scope.styleOpts.rightbarCollapsed);
                $scope.call.incoming = true;
                $scope.incoming_conn = conn;

                var params = {phone: $scope.call.from_number};
                Restangular.all('api/customer/byphone').post(params).then(function(data) {
                    if (data.customer.length == 0) {
                        $scope.call.inbound_name = "";
                    } else {
                        $scope.call.inbound_name = data.customer.customer.full_name;
                    }
                });
            });
            Twilio.Device.cancel(function(conn) {
                var params = {callid: conn.parameters.CallSid, isIncoming: $scope.call.incoming};
                Restangular.all('api/twilio/loginfo').post(params).then(function(data) {
                    // alert(data.success);
                });

                $scope.call.incoming = false;
                $scope.call.connecting = false;
                $scope.call.calling = false;
                $scope.incoming_conn = null;
                if ($scope.promise != null) {
                    $interval.cancel($scope.promise);
                    $scope.promise = null;
                }
            });
            Twilio.Device.disconnect(function (conn) {
                var params = {callid: conn.parameters.CallSid, isIncoming: $scope.call.incoming};
                Restangular.all('api/twilio/loginfo').post(params).then(function(data) {
                    // alert(data.success);
                });

                $scope.call.incoming = false;
                $scope.call.connecting = false;
                $scope.call.calling = false;
                $scope.incoming_conn = null;
                if ($scope.promise != null) {
                    $interval.cancel($scope.promise);
                    $scope.promise = null;
                }
            });

            $interval(function() {
                Restangular.all('api/contact/checksms').post().then(function(data) {
                    var unreads = data.data;
                    if (unreads != "none") {
                        for (var i = 0; i < unreads.length; i++) {
                            var title = 'New message arrived from ' + unreads[i].phone;
                            if (unreads[i].customer != '')
                                title = title + '(' + logs.customer + ')';
                            notificationService.notify({
                                title: title,
                                text: '' + unreads[i].content,
                                hide: false
                            });
                        }
                    }
                });
            }, 30*1000);
        });

        //Accept incoming
        $scope.acceptIncoming = function() {
            $scope.incoming_conn.accept();
            $scope.call.calling = true;
            $scope.call.connecting = true;
            $scope.call.timeCount = 0;
            $scope.promise = $interval(function(){
                $scope.call.timeCount ++;
                $scope.call.hhours = ("0" + Math.floor($scope.call.timeCount / 3600)).substr(-2);
                $scope.call.mminutes = ("0" + Math.floor($scope.call.timeCount / 60)).substr(-2);
                $scope.call.sseconds = ("0" + Math.floor($scope.call.timeCount % 60)).substr(-2);
            }, 1000);
        }

        //Reject incoming
        $scope.rejectIncoming = function() {
            if ($scope.call.connecting) {
                Twilio.Device.disconnectAll();
            } else {
                var params = {callid: $scope.incoming_conn.parameters.CallSid, isIncoming: $scope.call.incoming};
                $scope.incoming_conn.reject();
                Restangular.all('api/twilio/loginfo').post(params).then(function(data) {
                    alert(data.success);
                });
                $scope.call.incoming = false;
                $scope.call.connecting = false;
            }
            $scope.incoming_conn = null;
            // $global.set('rightbarCollapsed', !$scope.styleOpts.rightbarCollapsed);
        }
        
        // Call Phone
        $scope.callPhone = function () {
            // Call our ajax endpoint on the server to initialize the phone call
            var params = {"PhoneNumber": $scope.call.number};
            Twilio.Device.connect(params);
         
            Twilio.Device.connect(function (conn) {
                $scope.call.calling = true;
                $scope.call.connecting = true;
                $scope.call.timeCount = 0;
                $scope.promise = $interval(function(){
                    $scope.call.timeCount ++;
                    $scope.call.hhours = ("0" + Math.floor($scope.call.timeCount / 3600)).substr(-2);
                    $scope.call.mminutes = ("0" + Math.floor($scope.call.timeCount / 60)).substr(-2);
                    $scope.call.sseconds = ("0" + Math.floor($scope.call.timeCount % 60)).substr(-2);
                }, 1000);
            });
        };

        // Hangup Phone
        $scope.hangupPhone = function () {
            Twilio.Device.disconnectAll();
        };

        // Send Message
        $scope.send = new Object;
        $scope.sendMessage = function () {
            // Call our ajax endpoint on the server to send message
            var sms_data = {
                phoneNumber: $scope.send.MessageNumber,
                message: $scope.send.Message
            }
            Restangular.all('api/twilio/message').post(sms_data).then(function(data) {
                if (data.success == "true") {
                    Restangular.all('api/twilio/smsinfo').post().then(function(data) {
                    });
                }
            });
        };

        // Get Customers data
        $scope.customer_call = new Object();
        $scope.customer_msg = new Object();

        $scope.refreshCustomers = function(customer) {
            var param = { search_key: customer };
            Restangular.all('api/customer/list').post(param).then(function(data) {
                $scope.customers = data.list;
            });
        };

        $scope.callCustomerChanged = function() {
            $scope.call.number = $scope.customer_call.selected.phone;
        }

        $scope.msgCustomerChanged = function() {
            $scope.send.MessageNumber = $scope.customer_msg.selected.phone;
        }
    });