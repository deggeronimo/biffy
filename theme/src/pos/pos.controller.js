'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('PosQueueChanger', function($scope)
    {
        $scope.isSingleDayQueue = function()
        {
            if ($scope.currentWorkOrder == null)
            {
                return true;
            }

            switch (parseInt($scope.currentWorkOrder.queue))
            {
                case 0:
                    var currentTime = new Date($scope.currentWorkOrder.created_at);
                    currentTime.setHours(0, 0, 0, 0);

                    var updateTime = new Date($scope.currentWorkOrder.next_update);
                    updateTime.setHours(0, 0, 0, 0);

                    return currentTime <= updateTime && currentTime >= updateTime;
                case 1:
                    return true;
                case 2:
                    return false;
            }
        };       
        console.log('PosQueueChanger loaded');
    }
);

angular.module('biffyApp')
    .controller('PosMainController', function($rootScope, $global, $state, $scope, RestangularAppService, NotifierService) {
        
        $global.set('setMainBG', true);
        
        $scope.goto = function(location)
        {
            $state.go(location);
        };

        $scope.createNewSale = function()
        {
            var sale = {
                'customer_id' : null
            };

            RestangularAppService.all('sales').post(sale).then(
                function(result)
                {
                    var saleId = result[0].id;

                    $state.transitionTo('pos.checkout', {
                        'saleId' : saleId
                    });
                },
                function(data)
                {
                    NotifierService.error('Could not create sale: ' + JSON.stringify(data.data.messages));
                }
            );
        };
    }
);
