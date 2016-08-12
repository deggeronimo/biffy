'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'InventoryTrackingController',
    function($state, $scope, $location, ngTableParams, RestangularAppService, NotifierService)
    {
        $scope.storeItemId = $state.params.storeItemId;

        $scope.processInventoryItemList = function(inventoryItemList)
        {
            for (var i = 0; i < inventoryItemList.length; i++)
            {
                var inventoryItem = inventoryItemList[i];

                if (inventoryItem.sold_by_user != null)
                {
                    var saleItem = inventoryItem.sale_item;

                    saleItem.price = parseFloat(saleItem.price);
                    saleItem.labor_cost = parseFloat(saleItem.labor_cost);

                    saleItem.subtotal = saleItem.price + saleItem.labor_cost;
                    saleItem.taxesTotal = 0;

                    if (!saleItem.tax_exempt)
                    {
                        for (var j = 0; j < saleItem.taxes.length; j++)
                        {
                            var tax = saleItem.taxes[j];

                            tax.store_tax.percentage = parseFloat(tax.store_tax.percentage);

                            saleItem.taxesTotal += saleItem.subtotal * tax.store_tax.percentage;
                        }
                    }
                }
            }
        };

        $scope.backOrderedTableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                    }
                },
                $location.search()
            ), {
                total: 0,
                getData: function($defer, params)
                {
                    $location.search(params.url());

                    RestangularAppService.all('inventory?filter[store_item_id]=' + $scope.storeItemId + '&filter[back_order]=1&sorting[created_at]=desc').getList(params.url()).then(
                        function(result)
                        {
                            var data = result.plain();

                            $scope.processInventoryItemList(data);

                            $scope.inStockTableParams.settings({total: result.paginator.total});
                            $defer.resolve(data);
                        },
                        function()
                        {
                            NotifierService.error('Inventory Tracking could not be loaded');
                        }
                    );
                }
            }
        );

        $scope.inStockTableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                    }
                },
                $location.search()
            ), {
                total: 0,
                getData: function($defer, params)
                {
                    $location.search(params.url());

                    RestangularAppService.all('inventory?filter[store_item_id]=' + $scope.storeItemId + '&filter[in_stock]=1&sorting[created_at]=desc').getList(params.url()).then(
                        function(result)
                        {
                            var data = result.plain();

                            $scope.processInventoryItemList(data);

                            $scope.inStockTableParams.settings({total: result.paginator.total});
                            $defer.resolve(data);
                        },
                        function()
                        {
                            NotifierService.error('Inventory Tracking could not be loaded');
                        }
                    );
                }
            }
        );

        $scope.onOrderTableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                    }
                },
                $location.search()
            ), {
                total: 0,
                getData: function($defer, params)
                {
                    $location.search(params.url());

                    RestangularAppService.all('inventory?filter[store_item_id]=' + $scope.storeItemId + '&filter[on_order]=1&sorting[created_at]=desc').getList(params.url()).then(
                        function(result)
                        {
                            var data = result.plain();

                            $scope.processInventoryItemList(data);

                            $scope.onOrderTableParams.settings({total: result.paginator.total});
                            $defer.resolve(data);
                        },
                        function()
                        {
                            NotifierService.error('Inventory Tracking could not be loaded');
                        }
                    );
                }
            }
        );
    }
);
