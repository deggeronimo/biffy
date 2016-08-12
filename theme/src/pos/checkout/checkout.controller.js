'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'SaleEditController',
    function($scope, $global, $state, RestangularAppService, NotifierService, $modal, StoreService, $timeout, $q, UserService)
    {
        $global.set('setMainBG', true);

        $scope.init = function()
        {
            $scope.currentSaleId = $state.params.saleId;
            $scope.currentWorkOrder = null;
            $scope.workorderLoaded = false;

            $scope.userList = RestangularAppService.one('stores', StoreService.id()).all('users').getList().$object;

            $scope.workOrderNote = {};
        };

        // todo check permission, set requireApprovalDiscount = 100 if manager
        var requireApprovalDiscount = StoreService.config('discount-require-approval-percentage');

        $scope.init();

        $scope.reload = function()
        {
            $scope.loadSale();
        };

        $scope.loadSale = function()
        {
            $q.all([
                RestangularAppService.one('sales', $scope.currentSaleId).get(),
                RestangularAppService.all('workorder-statuses').getList({all: 1, 'filter[user_can_set]': 1})
            ]).then(function(result) {
                    $scope.workOrderStatuses = result[1];
                    result = result[0];
                    $scope.currentSale = result.plain();
                    $scope.companyDiscount = $scope.currentSale.company ? parseFloat($scope.currentSale.company.discount) : 0;

                    if ($scope.currentWorkOrder == null)
                    {
                        if ($scope.currentSale.work_orders.length > 0)
                        {
                            $scope.setCurrentWorkOrder($scope.currentSale.work_orders[0].id);
                        }
                    }
                    else
                    {
                        $scope.setCurrentWorkOrder($scope.currentWorkOrder.id);
                    }

                    for (var i=0; i<$scope.currentSale.work_orders.length; i++)
                    {
                        var workorder = $scope.currentSale.work_orders[i];

                        workorder.quickdiag = JSON.parse(workorder.quickdiag);
                        workorder.itemswithdevice = JSON.parse(workorder.itemswithdevice);
                    }
                },
                function()
                {

                }
            );
        };

        $scope.deleteSale = function()
        {
            RestangularAppService.one('sales', $scope.currentSaleId).remove().then(
                function()
                {
                    $state.transitionTo('pos');
                },
                function()
                {

                }
            );
        };

        $scope.addStoreItem = function(storeItem)
        {
            var saleItem = {
                sale_id: $scope.currentWorkOrder == null || storeItem.item.device_type_id == 1 ? $scope.currentSaleId : null,
                work_order_id: $scope.currentWorkOrder == null || storeItem.item.device_type_id == 1 ? null : $scope.currentWorkOrder.id,
                store_item_id: storeItem.id,
                company_id: $scope.currentSale.company ? $scope.currentSale.company_id : null,
                on_receipt: 1,
                tax_exempt: 0
            };

            $scope.addNewSaleItem(saleItem);
        };

        $scope.addNewSaleItem = function(saleItem)
        {
            var newSaleItem = angular.copy(saleItem);

            newSaleItem.company_id = $scope.currentSale.company ? $scope.currentSale.company_id : null;

            if ($scope.currentWorkOrder && $scope.currentWorkOrder.warranty_workorder_id)
            {
                newSaleItem.price = 0;
                newSaleItem.labor_cost = 0;
            }

            RestangularAppService.all('sale-items').post(newSaleItem).then(
                function()
                {
                    $scope.reload();
                },
                function()
                {

                }
            );
        };

        $scope.removeSaleItem = function(saleItem)
        {
            RestangularAppService.one('sale-items', saleItem.id).remove().then(
                function()
                {
                    $scope.reload();
                }
            );
        };

        $scope.setCurrentWorkOrder = function(id)
        {
            $timeout(function () {
                if ($scope.currentWorkOrder) {
                    $scope.currentWorkOrder.active = false;
                }

                var workOrder = $scope.findWorkOrderById(id);

                $scope.currentWorkOrder = workOrder;

                if ($scope.currentWorkOrder != null) {
                    $scope.currentWorkOrder.active = true;
                    $scope.currentDeviceTypeId = workOrder.device.device_type_id;
                }

                $scope.workorderLoaded = true;
            }, 1);
        };

        $scope.findWorkOrderById = function(id)
        {
            for (var i = 0; i < $scope.currentSale.work_orders.length; i ++)
            {
                var workOrder = $scope.currentSale.work_orders[i];

                if (workOrder.id == id)
                {
                    return workOrder;
                }
            }

            return null;
        };

        $scope.findSaleItemById = function(workOrder, id)
        {
            var saleItemList = workOrder == null ? $scope.currentSale.sale_items : workOrder.sale_items;

            for (var i=0; i<saleItemList.length; i++)
            {
                var saleItem = saleItemList[i];

                if (saleItem.id == id)
                {
                    return saleItem;
                }
            }

            return null;
        };

        $scope.saveTimer = null;
        $scope.stopSaveTimer = function () {
            if ($scope.saveTimer !== null) {
                $timeout.cancel($scope.saveTimer);
                $scope.saveTimer = null;
            }
        };

        $scope.startSaveTimer = function () {
            $scope.stopSaveTimer();

            $scope.saveTimer = $timeout(function () {
                $scope.saveAllTheThings();
            }, 10000);
        };

        $scope.calcItem = function (saleItem) {
            var validatedItem = {
                price : isNaN(parseFloat(saleItem.price))? 0 : parseFloat(saleItem.price),
                labor_cost : isNaN(parseFloat(saleItem.labor_cost))? 0 : parseFloat(saleItem.labor_cost),
                discount : isNaN(parseFloat(saleItem.discount))? 0 : parseFloat(saleItem.discount)
            };

            saleItem.subtotal = (validatedItem.price + validatedItem.labor_cost) * (1 - (validatedItem.discount + $scope.companyDiscount) / 100);
            saleItem.subtotal = parseFloat(saleItem.subtotal.toFixed(2));
        };

        $scope.dirtySaleItem = function(saleItem)
        {
            saleItem.dirty = true;
            $scope.currentSale.dirty = true;

            $scope.calcItem(saleItem);
            $scope.calcItem(saleItem.orig);

            var unitPrice = saleItem.inventory.store_item.unit_price;
            var approvalThreshold = unitPrice * (1 - (requireApprovalDiscount / 100));
            if(saleItem.subtotal <= approvalThreshold && saleItem.orig.subtotal > approvalThreshold) {
                $scope.stopSaveTimer();
                UserService.verifyManagerPin().then(function () {
                    $scope.startSaveTimer();
                }, function () {
                    saleItem.price = saleItem.orig.price;
                    saleItem.labor_cost = saleItem.orig.labor_cost;
                    saleItem.discount = saleItem.orig.discount;
                    $scope.calcItem(saleItem);
                    NotifierService.error('PIN entered did not have sufficient permissions');
                });
            } else {
                $scope.startSaveTimer();
            }
        };

        $scope.itemFocus = function () {
            $scope.stopSaveTimer();
        };

        $scope.saveStatus = {
            dirtyItems: 0,
            doingSave: false,
            doneSaving: false,
            savedItems: 0
        };

        $scope.saveAllTheThings = function()
        {
            $scope.stopSaveTimer();

            if ($scope.saveStatus.doingSave == true)
            {
                return;
            }

            $scope.saveStatus = {
                dirtyItems: 0,
                doingSave: true,
                doneSaving: false,
                savedItems: 0
            };

            for (var i = 0; i < $scope.currentSale.sale_items.length; i++)
            {
                var saleItem = $scope.currentSale.sale_items[i];

                if (saleItem.dirty === true)
                {
                    $scope.saveStatus.dirtyItems ++;
                    $scope.saveSaleItem(saleItem);
                }
            }

            for (i=0; i<$scope.currentSale.work_orders.length; i++)
            {
                var workOrder = $scope.currentSale.work_orders[i];

                for (var j = 0; j < workOrder.sale_items.length; j++)
                {
                    saleItem = workOrder.sale_items[j];

                    if (saleItem.dirty === true)
                    {
                        $scope.saveStatus.dirtyItems ++;
                        $scope.saveSaleItem(saleItem);
                    }
                }
            }

            $scope.saveStatus.doneSaving = true;
        };

        $scope.saveSaleItem = function(saleItem)
        {
            RestangularAppService.one('sale-items', saleItem.id).put(saleItem).then(
                function()
                {
                    $scope.saveStatus.savedItems ++;

                    if ($scope.saveStatus.doneSaving == true && $scope.saveStatus.savedItems == $scope.saveStatus.dirtyItems)
                    {
                        $scope.saveStatus.doingSave = false;

                        $scope.loadSale();
                    }
                },
                function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));

                    $scope.saveStatus.doingSave = false;
                }
            );
        };

        $scope.payment = {
            sale_id : 0,
            sale_payment_type_id : 1,
            amount : 0
        };

        $scope.adjustDiscount = function()
        {
            $scope.currentSale.money.value = parseFloat(($scope.currentSale.money.value / 100 * $scope.currentSale.money.total_due).toFixed(2));
        };

        $scope.adjustComp = function()
        {
            $scope.currentSale.money.value = $scope.currentSale.money.total_due;
        };

        $scope.makePayment = function(paymentType)
        {
            $scope.payment.sale_id = $scope.currentSale.id;
            $scope.payment.sale_payment_type_id = paymentType;
            $scope.payment.amount = $scope.currentSale.money.value;

            RestangularAppService.all('sale-payments').post($scope.payment).then(
                function()
                {
                    $scope.loadSale();
                },
                function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.openEditCustomerModal = function()
        {
            $modal.open(
                {
                    templateUrl : 'src/modals/editcustomer.modal.html',
                    controller : 'EditCustomerModalController',
                    size : 'lg',
                    resolve : {
                        customerId : function()
                        {
                            return $scope.currentSale.customer_id;
                        }
                    }
                }
            );
        };

        $scope.saleIsQuote = function()
        {
            return $scope.currentSale && $scope.currentSale.quote_id && $scope.currentSale.quote_id != null;
        };

        $scope.saveAsQuote = function()
        {
            var quote = {
                customer_id : $scope.currentSale.customer_id,
                subtotal : $scope.currentSale.money.subtotal,
                taxes : $scope.currentSale.money.taxes_due
            };

            RestangularAppService.all('quotes').post(quote).then(
                function()
                {
                    $scope.deleteSale();
                },
                function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.autoDiscount = function()
        {
            var i, j;
            var mostExpensiveItem = null;
            var saleItem;

            var saleItemList = $scope.currentSale.sale_items;

            for (i = 0; i < saleItemList.length; i ++)
            {
                saleItem = saleItemList[i];
                saleItem.price = parseFloat(parseFloat(saleItem.price).toFixed(2));

                if (mostExpensiveItem == null || saleItem.price > mostExpensiveItem.price)
                {
                    mostExpensiveItem = saleItem;
                }
            }

            for (i = 0; i < saleItemList.length; i ++)
            {
                saleItem = saleItemList[i];

                if (saleItem != mostExpensiveItem)
                {
                    saleItem.discount = 50;
                    $scope.dirtySaleItem(saleItem);
                }
            }

            for (i = 0; i < $scope.currentSale.work_orders.length; i ++)
            {
                saleItemList = $scope.currentSale.work_orders[i].sale_items;

                mostExpensiveItem = saleItemList[0];

                for (j = 1; j < saleItemList.length; j ++)
                {
                    saleItem = saleItemList[i];
                    saleItem.price = parseFloat(parseFloat(saleItem.price).toFixed(2));

                    if (mostExpensiveItem == null || saleItem.price > mostExpensiveItem.price)
                    {
                        mostExpensiveItem = saleItem;
                    }
                }

                for (j = 0; j < saleItemList.length; j ++)
                {
                    saleItem = saleItemList[j];

                    if (saleItem != mostExpensiveItem)
                    {
                        saleItem.discount = 50;
                        $scope.dirtySaleItem(saleItem);
                    }
                }
            }
        };

        $scope.lockTradeCredit = function()
        {
            return $scope.currentSale.company && $scope.currentSale.company.company_instructions.lock_trade_credit == '1';
        };

        $scope.createWorkOrderNote = function()
        {
            RestangularAppService.one('workorders', $scope.currentWorkOrder.id).all('notes').post($scope.workOrderNote).then(
                function(result)
                {
                    $scope.currentWorkOrder.workorder_status_id = $scope.workOrderNote.workorder_status_id;

                    var query = {
                        all: 1,
                        sorting: {
                            created_at: 'desc'
                        }
                    };

                    RestangularAppService.one('workorders', $scope.currentWorkOrder.id).all('notes').getList(flattenParams(query)).then(
                        function(result)
                        {
                            $scope.currentWorkOrder.work_order_notes = result
                        },
                        function()
                        {

                        }
                    )
                },
                function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.assignUserToCurrentWorkOrder = function(user)
        {
            RestangularAppService.one('workorders', $scope.currentWorkOrder.id).put({assigned_to_user_id: user.id}).then(
                function()
                {
                    $scope.currentWorkOrder.assigned_to_user = user;
                },
                function()
                {

                }
            )
        };

        $scope.createWarranty = function()
        {
            $state.transitionTo('pos.device', {
                action: 'warranty',
                customerId: $scope.currentSale.customer_id,
                deviceId: $scope.currentWorkOrder.device_id,
                saleId: $scope.currentWorkOrder.id
            });
        };

        $scope.saleCompleted = function()
        {
            return $scope.currentSale && ($scope.currentSale.completed == 1);
        };

        $scope.checkTaxExempt = function(event)
        {
            var result = event.currentTarget.id.split('-');

            var workOrder = $scope.findWorkOrderById(result[1]);
            var saleItem = $scope.findSaleItemById(workOrder, result[3]);

            saleItem.tax_exempt = saleItem.tax_exempt == 0 ? 1 : 0;

            $scope.dirtySaleItem(saleItem);
        };

        $scope.loadSale();

        $scope.swapItem = function (current) {
            var modalInstance = $modal.open({
                size: 'lg',
                templateUrl: 'src/pos/checkout/swap-inventory/swap-inventory.modal.html',
                controller: function ($scope, current, inventory, $modalInstance) {
                    $scope.current = current;
                    $scope.inventory = inventory;

                    $scope.select = function (item) {
                        $modalInstance.close(item);
                    };
                },
                resolve: {
                    current: function () {
                        return current.inventory;
                    },
                    inventory: function () {
                        var deferred = $q.defer();
                        RestangularAppService.all('inventory').getList({'filter[in_stock]': 1, 'sorting[created_at]': 'desc', 'filter[store_item_id]': current.store_item_id}).then(function (data) {
                            deferred.resolve(data);
                        });
                        return deferred.promise;
                    }
                }
            });

            modalInstance.result.then(function (item) {
                RestangularAppService.one('sale-items', current.id).customPUT({inventory_id: item.id}).then(function () {
                    $scope.reload();
                });
            });
        };
    }
);
