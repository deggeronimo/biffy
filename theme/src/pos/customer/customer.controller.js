'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'CustomerInfoController',
    function($rootScope, $state, $scope, $global, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService, StoreService)
    {
        $global.set('setMainBG', true);

        $scope.currentCustomer = null;
        $scope.newCustomer = {};
        $scope.requireReferralSource = StoreService.config('require-referral-source') == '1';

        $scope.selectCustomer = function(customer)
        {
            $scope.loadCustomer(customer.id);
        };

        $scope.loadCustomer = function(id)
        {
            RestangularAppService.one('customers', id).get().then(
                function(data)
                {
                    $scope.currentCustomer = data.plain();
                },
                function(data)
                {
                }
            );
        };

        $scope.createCustomer = function()
        {
            RestangularAppService.all('customers').post($scope.newCustomer).then(
                function(data)
                {
                    $scope.currentCustomer = data;

                    $scope.addNewDevice();
                },
                function(data)
                {
                    NotifierService.error('Could not add data');
                }
            );
        };

        $scope.createNewWorkOrder = function(deviceId)
        {
            var sale = {
                'customer_id' : $scope.currentCustomer.id ? $scope.currentCustomer.id : 0
            };

            RestangularAppService.all('sales').post(sale).then(
                function(result)
                {
                    var saleId = result.id;

                    $state.transitionTo('pos.device', {
                        'action' : 'workorder',
                        'customerId' : $scope.currentCustomer.id,
                        'deviceId' : deviceId,
                        'saleId' : saleId
                    });
               },
                function(data)
                {
                    NotifierService.error('Could not create sale: ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.addNewDevice = function()
        {
            $state.transitionTo('pos.device', {
                action: 'new',
                customerId: $scope.currentCustomer.id,
                deviceId: 0,
                saleId: 0
            });
        };
    }
);
