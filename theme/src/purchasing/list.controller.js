'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'PurchasingHomeController',
    function($scope, $state, $stateParams, NotifierService, RestangularAppService, ngTableParams, $location, $global)
    {
        $global.set('setMainBG', true);

        $scope.shippingMethodList = [
            '', 'UPS Ground', 'UPS 3 Day Select', 'UPS 2nd Day Air', 'UPS Next Day Air Saver', 'UPS Next Day Air',
            'Local Pick Up', 'Other'
        ];

        $scope.autoOrder = function()
        {
            RestangularAppService.one('autoorder').get().then(
                function(result)
                {
                    $state.transitionTo(
                        'purchasing.edit',
                        {
                            id : result.id
                        }
                    );
                },
                function()
                {

                }
            );
        };

        $scope.deletePurchaseOrder = function(purchaseOrder)
        {
            RestangularAppService.one('purchase', purchaseOrder.id).remove().then(
                function()
                {
                    if ($scope.currentPurchaseOrder != null && $scope.currentPurchaseOrder.id == purchaseOrder.id)
                    {
                        $scope.currentPurchaseOrder = null;
                    }

                    $scope.tableParams.reload();
                },
                function()
                {

                }
            )
        };

        $scope.viewPurchaseOrder = function(purchaseOrder)
        {
            console.log(purchaseOrder);

            $scope.currentPurchaseOrder = purchaseOrder.plain();
            $scope.currentPurchaseOrder.total = parseFloat($scope.currentPurchaseOrder.subtotal) + parseFloat($scope.currentPurchaseOrder.taxes);

            console.log($scope.currentPurchaseOrder);
        };

        $scope.vendorList = RestangularAppService.all('vendors').getList().$object;

        $scope.getVendorById = function(id)
        {
            for (var i=0; i<$scope.vendorList.length; i++)
            {
                var vendor = $scope.vendorList[i];

                if (vendor.id == id)
                {
                    return vendor;
                }
            }

            return null;
        };

        $scope.tableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                        created_at: 'desc'
                    }
                },
                $location.search()
            ), {
                total: 0,
                getData: function($defer, params)
                {
                    $location.search(params.url());
                    RestangularAppService.all('purchase').getList(params.url()).then(
                        function(result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        },
                        function()
                        {
                            NotifierService.error('Purchase Orders could not be loaded');
                        }
                    );
                }
            }
        );
    }
);
