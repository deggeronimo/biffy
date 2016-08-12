'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('EditCustomerModalController',
        function(customerId, $scope, $modalInstance, RestangularAppService)
        {
            $scope.init = function(id)
            {
                $scope.currentCustomer = RestangularAppService.one('customers', id).get().then(
                    function (result)
                    {
                        $scope.currentCustomer = result;
                    },
                    function ()
                    {
                        $scope.cancel();
                    }
                );
            };

            $scope.customerId = customerId;
            $scope.init($scope.customerId);

            $scope.cancel = function()
            {
                $modalInstance.dismiss('cancel');
            };

            $scope.save = function()
            {
                if ($scope.currentCustomer == null)
                {
                    NotifierService.error('Cannot save unloaded customer.  Please try again.');
                }
                else
                {
                    $scope.currentCustomer.put().then(
                        function(data)
                        {
                            $modalInstance.close($scope.currentCustomer);
                        },
                        function(data)
                        {
                            NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                        }
                    );
                }
            };
        }
    );