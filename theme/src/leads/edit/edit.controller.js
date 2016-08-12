'use strict';

angular.module('biffyApp').controller(
    'LeadsEditController',
    function($q, $scope, $stateParams, $state, RestangularAppService, NotifierService)
    {
        $scope.id = $stateParams.id || null;
        $scope.data = {};
        $scope.mode = 'Loading';

        $scope.isAdd = function()
        {
            return $scope.id === null;
        };

        $scope.isEdit = function()
        {
            return $scope.mode === 'Edit';
        };

        if($scope.isAdd())
        {
            $scope.mode = 'Add';
        }
        else
        {
            RestangularAppService.one('leads', $scope.id).get().then(
                function(data)
                {
                    $scope.mode = 'Edit';
                    $scope.data = data;
                },
                function(data)
                {
                    NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
                }
            );
        }

        $scope.store = function()
        {
            RestangularAppService.all('leads').post($scope.data).then(
                function()
                {
                    $state.transitionTo('leads');
                },
                function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.update = function()
        {
            $scope.data.put().then(
                function()
                {
                    $state.transitionTo('leads');
                },
                function(data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.destroy = function()
        {
            $scope.data.remove().then(
                function()
                {
                    $state.transitionTo('leads');
                },
                function(data)
                {
                    NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.cancel = function()
        {
            $state.transitionTo('leads');
        };

        $scope.createWorkOrder = function()
        {
            //1) Create a Customer from the Data
            var customer = {
                'given_name' : $scope.data.given_name,
                'family_name' : $scope.data.family_name,
                'phone' : $scope.data.phone,
                'email' : $scope.data.email,
                'postal_code' : $scope.data.postal_code,
                'referral_source' : 'Online Quote'
            };

            RestangularAppService.all('customers').post(customer).then(
                function(data)
                {
                    //2) Transition to the Device Selection Screen
                    $state.transitionTo('pos.device',
                        {
                            'action' : 'new',
                            'customerId' : data[0].id,
                            'deviceId' : 0,
                            'saleId' : 0
                        }
                    );
                },
                function(data)
                {
                    NotifierService.error('Could not create a Customer ' + JSON.stringify(data.data.messages));
                }
            );

        };
    }
);