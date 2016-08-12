'use strict';

angular.module('biffyApp').controller('AppointmentEditController',
    function($rootScope, $state, $scope, $stateParams, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService)
    {
        $scope.customerList = RestangularAppService.all('customers').getList().$object;
        $scope.storeList = RestangularAppService.all('stores').getList().$object;

        $scope.selectCustomer = function(customer)
        {
            $scope.data.customer = customer;
            $scope.data.customer_id = customer.id;
        };

        $scope.setStore = function(id)
        {
            $scope.data.store_id = id;
        };

        $scope.appointmentStatuses = [
            '', 'Pending', 'Canceled'
        ];

        $scope.appointmentStatusRange = function()
        {
            var result = [];
            for (var i = 1; i < $scope.appointmentStatuses.length; i++)
            {
                result.push($scope.appointmentStatuses[i]);
            }
            return result;
        };

        $scope.today = function()
        {
            $scope.dt = new Date();

            $scope.dt.setHours(10);
            $scope.dt.setMinutes(0);
            $scope.dt.setSeconds(0);
        };
        $scope.today();
        $scope.minDate = new Date();

        $scope.disabled = function(date, mode)
        {
            //TODO: Get Blackout Dates/Times
            return ( mode === 'day' && ( date.getDay() === 0 || date.getDay() === 6 ) );
        };

        $scope.$watch('dt',
            function(newValue, oldValue)
            {
                newValue.setHours(oldValue.getHours());
                newValue.setMinutes(oldValue.getMinutes());
            }
        );

        $scope.selectedTime = 0;

        $scope.$watch('selectedTime',
            function(newValue)
            {
                $scope.dt.setHours(Math.floor(newValue / 2) + 10);
                $scope.dt.setMinutes(newValue % 2 == 1 ? 30 : 0);
            }
        );

        $scope.id = $stateParams.id || null;
        $scope.mode = 'Loading';

        $scope.data = {
            appointment_status_id : 1
        };

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
            RestangularAppService.one('appointments', $scope.id).get().then(
                function(data)
                {
                    $scope.mode = 'Edit';
                    $scope.data = data;

                    $scope.dt = new Date();
                },
                function(data)
                {
                    NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
                }
            );
        }

        $scope.store = function()
        {
            $scope.data.time = Math.floor($scope.dt.getTime()/1000);

            console.log($scope.data);

            RestangularAppService.all('appointments').post($scope.data).then(
                function()
                {
                    $state.transitionTo('appointments.list');
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
                    $state.transitionTo('appointments.list');
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
                    $state.transitionTo('appointments.list');
                },
                function(data)
                {
                    NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.cancel = function()
        {
            $state.transitionTo('appointments.list');
        };
    }
);