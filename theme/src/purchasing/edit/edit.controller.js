'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'PurchasingEditController',
    function($scope, $state, $stateParams, NotifierService, RestangularAppService, $global)
    {
        $global.set('setMainBG', true);

        $scope.id = $stateParams.id || null;
        $scope.mode = 'Loading';

        $scope.isAdd = function()
        {
            return $scope.id === null;
        };

        $scope.isEdit = function()
        {
            return $scope.mode === 'Edit';
        };

        $scope.isFinalized = function()
        {
            return $scope.mode === 'Receive';
        };

        $scope.loadPurchaseOrder = function()
        {
            RestangularAppService.one('purchase', $scope.id).get().then(
                function (data)
                {
                    $scope.itemsToReceive = 0;

                    $scope.orderItemList = [];

                    $scope.money.subtotal = 0;
                    $scope.money.taxes = 0;

                    $scope.currentPurchaseOrder = data.plain();

                    $scope.currentPurchaseOrder.vendor_id = parseInt($scope.currentPurchaseOrder.vendor_id);
                    $scope.lastVendorId = $scope.currentPurchaseOrder.vendor_id;

                    $scope.currentPurchaseOrder.shipping_method_id = parseInt($scope.currentPurchaseOrder.shipping_method_id);

                    $scope.purchaseItemList = $scope.currentPurchaseOrder.purchase_items;

                    for (var i=0; i<$scope.purchaseItemList.length; i ++)
                    {
                        var purchaseItem = $scope.purchaseItemList[i];

                        purchaseItem.store_item.stock = parseInt(purchaseItem.store_item.stock);
                        purchaseItem.store_item.to_order = parseInt(purchaseItem.quantity);
                        purchaseItem.store_item.subtotal = 0;

                        purchaseItem.store_item.name = purchaseItem.store_item.item.name;
                        purchaseItem.store_item.distro_price = purchaseItem.store_item.item.distro_price;

                        purchaseItem.to_receive = parseInt(purchaseItem.quantity);
                        purchaseItem.received = 0;

                        $scope.itemsToReceive += purchaseItem.to_receive;

                        if (purchaseItem.receive_items != null)
                        {
                            for (var j = 0; j < purchaseItem.receive_items.length; j++)
                            {
                                var receiveItem = purchaseItem.receive_items[j];

                                purchaseItem.received += parseInt(receiveItem.quantity);
                            }
                        }

                        purchaseItem.price = purchaseItem.cost;

                        $scope.updatePurchaseItemSubtotal(purchaseItem);
                    }

                    $scope.dirty = false;

                    if ($scope.currentPurchaseOrder.finalized == '1')
                    {
                        $scope.mode = 'Receive';

                        $scope.negativeInventoryItemList = [];
                    }
                    else
                    {
                        $scope.mode = 'Edit';

                        $scope.suggestNegativeInventoryItems();
                    }
                },
                function ()
                {

                }
            );
        };

        $scope.suggestNegativeInventoryItems = function()
        {
            var query = {
                all: 1,
                filter: {
                    negative_stock: 1
                },
                sorting: {
                    stock: 'asc'
                }
            };

            RestangularAppService.all('storeitems').getList(flattenParams(query)).then(
                function (data)
                {
                    $scope.negativeInventoryItemList = data.plain();

                    for (var i = 0; i < $scope.negativeInventoryItemList.length; i++)
                    {
                        var negativeInventoryItem = $scope.negativeInventoryItemList[i];
                        var purchaseItem = $scope.findPurchaseItemById(negativeInventoryItem.id);

                        if (purchaseItem != null)
                        {
                            $scope.negativeInventoryItemList.splice($scope.negativeInventoryItemList.indexOf(negativeInventoryItem), 1);
                            i --;
                        }
                    }
                },
                function (data)
                {

                }
            );
        };

        $scope.findPurchaseItemByStoreItemId = function(id)
        {
            for (var i=0; i<$scope.purchaseItemList.length; i++)
            {
                var purchaseItem = $scope.purchaseItemList[i];

                if (purchaseItem.store_item.id == id)
                {
                    return purchaseItem;
                }
            }

            return null;
        };

        $scope.findPurchaseItemById = function(id)
        {
            for (var i=0; i<$scope.purchaseItemList.length; i++)
            {
                var purchaseItem = $scope.purchaseItemList[i];

                if (purchaseItem.store_item_id == id)
                {
                    return purchaseItem;
                }
            }

            return null;
        };

        $scope.addAllTheNegativeItems = function()
        {
            while ($scope.negativeInventoryItemList.length > 0)
            {
                var inventoryItem = $scope.negativeInventoryItemList[0];

                $scope.addNegativeItem(inventoryItem);
            }
        };

        $scope.addNegativeItem = function(inventoryItem)
        {
            $scope.negativeInventoryItemList.splice($scope.negativeInventoryItemList.indexOf(inventoryItem), 1);
            $scope.orderItemList.push(inventoryItem);

            inventoryItem.price = inventoryItem.distro_price;

            inventoryItem.to_order = inventoryItem.to_order ? inventoryItem.to_order : - inventoryItem.stock;
            inventoryItem.subtotal = 0;

            $scope.updateOrderItemSubtotal(inventoryItem);

            $scope.dirty = true;
        };

        $scope.calculateTaxes = function()
        {
            var i, taxesDue = 0;

            for (i = 0; i < this.purchaseItemList.length; i++)
            {
                var purchaseItem = this.purchaseItemList[i];

                taxesDue += (purchaseItem.store_item.subtotal) * (0.065);
            }

            for (i = 0; i < this.orderItemList.length; i++)
            {
                var orderItem = this.orderItemList[i];

                taxesDue += (orderItem.subtotal) * (0.065);
            }

            $scope.money.taxes = parseFloat(taxesDue.toFixed(2));
        };

        $scope.finalizeOrder = function()
        {
            RestangularAppService.one('purchase', $scope.id).put(
                { finalized : 1 }
            ).then(
                function()
                {
                    $scope.loadPurchaseOrder();
                },
                function()
                {
                }
            );
        };

        $scope.receiveAllTheThings = function()
        {
            for (var i=0; i<$scope.purchaseItemList.length; i++)
            {
                var purchaseItem = $scope.purchaseItemList[i];

                $scope.receivePurchaseItem(purchaseItem);
            }
        };

        $scope.receivePurchaseItem = function(purchaseItem)
        {
            purchaseItem.to_receive = parseInt(purchaseItem.to_receive);

            var toReceive = purchaseItem.to_receive;
            toReceive = toReceive > purchaseItem.quantity ? purchaseItem.quantity : toReceive;

            if (toReceive <= 0)
            {
                return;
            }

            RestangularAppService.one('purchaseitem', purchaseItem.id).all('receive').post({ quantity : toReceive }).then(
                function(result)
                {
                    var quantity = parseInt(result.quantity);

                    purchaseItem.store_item.stock += quantity;
                    purchaseItem.store_item.on_order -= quantity;

                    purchaseItem.received += quantity;
                    purchaseItem.to_receive = purchaseItem.store_item.on_order;

                    $scope.itemsToReceive -= toReceive;
                },
                function()
                {
                }
            );
        };

        $scope.removeOrderItem = function(orderItem)
        {
            $scope.orderItemList.splice($scope.orderItemList.indexOf(orderItem), 1);

            //We have to set to_order to update the subtotal to 0, but store this amount in case the user readds
            var toOrder = orderItem.to_order;

            orderItem.to_order = 0;
            $scope.updateOrderItemSubtotal(orderItem);
            orderItem.to_order = toOrder;

            if (orderItem.stock < 0)
            {
                $scope.negativeInventoryItemList.push(orderItem);
            }

            $scope.dirty = true;
        };

        $scope.removePurchaseItem = function(purchaseItem)
        {
            $scope.purchaseItemList.splice($scope.purchaseItemList.indexOf(purchaseItem), 1);

            //We have to set to_order to update the subtotal to 0, but store this amount in case the user readds
            var toOrder = purchaseItem.store_item.to_order;

            purchaseItem.store_item.to_order = 0;
            $scope.updateOrderItemSubtotal(purchaseItem);
            purchaseItem.store_item.to_order = toOrder;

            if (parseInt(purchaseItem.store_item.stock) < 0)
            {
                $scope.negativeInventoryItemList.push(purchaseItem.store_item);
            }

            RestangularAppService.one('purchaseitem', purchaseItem.id).remove();
        };

        $scope.saveAllTheThings = function()
        {
            document.getElementById('save-button').disabled = true;

            if ($scope.isAdd())
            {
                RestangularAppService.all('purchase').post($scope.currentPurchaseOrder).then(
                    function(result)
                    {
                        $scope.id = result.id;
                        $scope.currentPurchaseOrder = result.plain();

                        $scope.savedItems = 0;

                        $scope.saveOrderItems();

                        $scope.mode = 'Edit';

                        if ($scope.orderItemList.length + $scope.purchaseItemList.length == 0)
                        {
                            $scope.loadPurchaseOrder();
                            document.getElementById('save-button').disabled = false;
                        }
                    },
                    function()
                    {
                        document.getElementById('save-button').disabled = false;
                    }
                );
            }
            else
            {
                RestangularAppService.one('purchase', $scope.currentPurchaseOrder.id).put($scope.currentPurchaseOrder).then(
                    function()
                    {
                        RestangularAppService.one('purchase', $scope.id).put($scope.currentPurchaseOrder).then(
                            function(result)
                            {
                                $scope.savedItems = 0;

                                $scope.saveOrderItems();
                                $scope.savePurchaseItems();

                                if ($scope.orderItemList.length + $scope.purchaseItemList.length == 0)
                                {
                                    $scope.loadPurchaseOrder();
                                    document.getElementById('save-button').disabled = false;
                                }
                            },
                            function()
                            {
                                document.getElementById('save-button').disabled = false;
                            }
                        );
                    },
                    function()
                    {
                        document.getElementById('save-button').disabled = false;
                        $scope.dirty = false;
                    }
                );
            }
        };

        $scope.saveOrderItems = function()
        {
            for (var i=0; i<$scope.orderItemList.length; i++)
            {
                var orderItem = $scope.orderItemList[i];

                var purchaseItem = {
                    purchase_order_id : $scope.id,
                    store_item_id : orderItem.id,
                    quantity : orderItem.to_order ? orderItem.to_order : 0,
                    cost : parseFloat(orderItem.price).toFixed(2)
                };

                RestangularAppService.all('purchaseitem').post(purchaseItem).then(
                    function()
                    {
                        $scope.savedItems ++;

                        if ($scope.savedItems == $scope.orderItemList.length + $scope.purchaseItemList.length)
                        {
                            $scope.loadPurchaseOrder();
                            document.getElementById('save-button').disabled = false;
                        }
                    },
                    function(data)
                    {
                        NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));

                        document.getElementById('save-button').disabled = false;
                        $scope.savedItems = null;
                    }
                );
            }
        };

        $scope.savePurchaseItems = function()
        {
            for (var i=0; i<$scope.purchaseItemList.length; i++)
            {
                var purchaseItem = $scope.purchaseItemList[i];

                purchaseItem.cost = purchaseItem.price;

                RestangularAppService.one('purchaseitem', purchaseItem.id).put(purchaseItem).then(
                    function()
                    {
                        $scope.savedItems ++;

                        if ($scope.savedItems == $scope.orderItemList.length + $scope.purchaseItemList.length)
                        {
                            $scope.loadPurchaseOrder();
                            document.getElementById('save-button').disabled = false;
                        }
                    },
                    function(data)
                    {
                        NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));

                        document.getElementById('save-button').disabled = false;
                        $scope.savedItems = null;
                    }
                );
            }
        };

        $scope.lastVendorId = 1;

        $scope.updateOrderItemPrices = function(vendorId)
        {
            if (vendorId == 1)
            {
                for (var i=0; i<$scope.orderItemList.length; i++)
                {
                    var orderItem = $scope.orderItemList[i];

                  priceerItem.price = orderItem.distro_price;
                    $scope.updateOrderItemSubtotal(orderItem);
                }
            }
            else if ($scope.lastVendorId == 1)
            {
                for (i=0; i<$scope.orderItemList.length; i++)
                {
                    orderItem = $scope.orderItemList[i];

                    $scope.updateOrderItemSubtotal(orderItem);
                }
            }

            $scope.lastVendorId = vendorId;
        };

        $scope.updateOrderItemSubtotal = function(orderItem)
        {
            var validatedItem = {
                price : isNaN(parseFloat(orderItem.price))? 0 : parseFloat(orderItem.price),
                to_order : isNaN(parseInt(orderItem.to_order))? 0 : parseInt(orderItem.to_order)
            };

            $scope.money.subtotal = parseFloat(($scope.money.subtotal - orderItem.subtotal).toFixed(2));
            orderItem.subtotal = parseFloat((validatedItem.to_order * validatedItem.price).toFixed(2));
            $scope.money.subtotal = parseFloat(($scope.money.subtotal + orderItem.subtotal).toFixed(2));

            $scope.calculateTaxes();

            $scope.dirty = true;
        };

        $scope.updatePurchaseItemSubtotal = function(purchaseItem)
        {
            var validatedItem = {
                price : isNaN(parseFloat(purchaseItem.price))? 0 : parseFloat(purchaseItem.price),
                to_order : isNaN(parseInt(purchaseItem.store_item.to_order))? 0 : parseInt(purchaseItem.store_item.to_order),
                received : isNaN(parseInt(purchaseItem.received))? 0 : parseInt(purchaseItem.received)
            };

            purchaseItem.quantity = purchaseItem.store_item.to_order;

            $scope.money.subtotal = parseFloat(($scope.money.subtotal - purchaseItem.store_item.subtotal).toFixed(2));
            purchaseItem.store_item.subtotal = parseFloat(((validatedItem.to_order + validatedItem.received) * validatedItem.price).toFixed(2));
            $scope.money.subtotal = parseFloat(($scope.money.subtotal + purchaseItem.store_item.subtotal).toFixed(2));

            $scope.calculateTaxes();

            $scope.dirty = true;
        };

        if($scope.isAdd())
        {
            $scope.mode = 'Create';

            $scope.currentPurchaseOrder = {
                currency_rate : 1,
                vendor_id : 1,
                tracking_number : '',
                shipping_method : 'UPS Ground',
                shipping_cost : 0,
                finalized : 0
            };

            $scope.suggestNegativeInventoryItems();
        }
        else
        {
            $scope.loadPurchaseOrder();
        }

        $scope.money = {
            subtotal : 0,
            taxes : 0,
            totalDue : 0
        };

        $scope.itemsToReceive = 0;

        $scope.purchaseItemList = [];
        $scope.orderItemList = [];

        $scope.dirty = false;

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

        $scope.shippingMethodList = [
            '', 'UPS Ground', 'UPS 3 Day Select', 'UPS 2nd Day Air', 'UPS Next Day Air Saver', 'UPS Next Day Air',
            'Local Pick Up', 'Other'
        ];

        $scope.shippingMethodsRange = function()
        {
            var result = [];
            for (var i = 1; i < $scope.shippingMethodList.length; i++)
            {
                result.push($scope.shippingMethodList[i]);
            }
            return result;
        };
    }
);
