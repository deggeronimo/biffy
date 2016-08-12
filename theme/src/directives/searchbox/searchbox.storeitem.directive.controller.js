'use strict';

angular.module('biffyApp').directive(
    'storeItemSearchBox',
    function (RestangularAppService)
    {
        function link(scope)
        {
            scope.getStoreItems = function (val)
            {
                var query = {
                    all: 1,
                    filter: {
                        search: val
                    }
                };

                if (typeof scope.currentDeviceTypeId !== 'undefined')
                {
                    query.device_type_id = scope.currentDeviceTypeId;
                }

                console.log('Searching StoreItems: ', query);

                return RestangularAppService.all('storeitems').getList(flattenParams(query)).then(
                    function (response)
                    {
                        return response;
                    }
                );
            };

            scope.addStoreItem = function(storeItem)
            {
                if (scope.currentPurchaseOrder != null)
                {
                    scope.orderItemList.push(storeItem);

                    storeItem.price = storeItem.distro_price;

                    storeItem.to_order = storeItem.to_order ? storeItem.to_order : 0;
                    storeItem.subtotal = 0;

                    scope.updateOrderItemSubtotal(storeItem);

                    scope.dirty = true;
                }
                else if (scope.currentWorkOrder || scope.currentSale)
                {
                    var saleItem = {
                        sale_id: scope.currentWorkOrder == null || storeItem.item.device_type_id == 1 ? scope.currentSaleId : null,
                        work_order_id: scope.currentWorkOrder == null || storeItem.item.device_type_id == 1 ? null : scope.currentWorkOrder.id,
                        store_item_id: storeItem.id,
                        price: storeItem.unit_price,
                        labor_cost: storeItem.labor_cost,
                        discount: 0,
                        on_receipt: 1,
                        tax_exempt: 0
                    };

                    if (saleItem.sale_id == null && saleItem.work_order_id == null)
                    {
                        return;
                    }

                    scope.addNewSaleItem(saleItem);
                }
                else
                {
                    scope.currentStoreItem = storeItem;
                }
            };

            scope.addNewSaleItem = function(saleItem)
            {
                RestangularAppService.all('sale-items').post(saleItem).then(
                    function()
                    {
                        scope.reload();
                    },
                    function()
                    {

                    }
                );
            };

            scope.removeSaleItem = function(saleItem)
            {
                RestangularAppService.one('sale-items', saleItem.id).remove().then(
                    function()
                    {
                        scope.reload();
                    }
                );
            };
        }

        return {
            link : link,
            templateUrl : 'src/directives/searchbox/searchbox.storeitem.directive.template.html'
        };
    }
);