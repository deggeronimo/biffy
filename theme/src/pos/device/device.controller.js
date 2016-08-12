'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'DeviceSelectionController',
    function($rootScope, $state, $scope, $global, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService)
    {   
        $global.set('setMainBG', true);

        $scope.checklistList = null;
        $scope.itemChecklistList = null;

        $scope.notes = { 'initialCondition' : '', 'deviceRating' : 1 };

        $scope.ratingDescriptions = [
            '',
            'Very Poor. Device severely, physically, and/or cosmetically damaged.  Device broken apart, pieces missing, known liquid damage, scuffs, scratches, dents all over etc.',
            'Poor. Physically damaged components (cracked glass/lcd for instance), known liquid damage and minor to severe cosmetic wear.  Device still in one piece.',
            'Good. Minor amounts of physical damage visible (cracked glass for instance). Does not include known liquid damage. Regular use cosmetic wear (minor scratches, scuffs, dents in more than 5 areas)',
            'Very good. No visible physical damage. Little to almost no cosmetic wear. (No more than 3-5 small, minor cosmetic blemishes.)',
            'Like New. No visible physical damage or cosmetic wear.'
        ];

        $scope.serialTypes = [ { 'name' : 'Software' }, { 'name' : 'Hardware' } ];

        $scope.status =
        {
            isopen: false
        };

        $scope.isopena = true;

        $scope.notes.deviceRating = 1;

        $scope.deviceBreadCrumbList = [];

        $scope.resetDeviceSelection = function()
        {
            $scope.getDeviceTypesWithParent(0);

            $scope.selectedDeviceType = null;
            $scope.deviceBreadCrumbList = [];

            $scope.selectedDeviceRepair = null;
        };

        $scope.breadCrumbGoBackTo = function(crumb)
        {
            for (var i=0; i<$scope.deviceBreadCrumbList.length; i++)
            {
                var breadCrumb = $scope.deviceBreadCrumbList[i];

                if (breadCrumb.id == crumb.id)
                {
                    $scope.deviceBreadCrumbList.splice(i, 99);

                    $scope.selectedDeviceType = null;
                    $scope.selectDeviceType(crumb);
                    break;
                }
            }

            $scope.selectedDeviceRepair = null;
        };

        $scope.getDeviceTypesWithParent = function(id)
        {
            var query = { all: 1, filter: {} };

            if (id == 0)
            {
                query.filter.top = 1;
            }
            else
            {
                query.filter.parent_id = id;
            }

            RestangularAppService.all('devicetypes').getList(flattenParams(query)).then(
                function(result)
                {
                    $scope.deviceTypeList = result.plain();
                },
                function()
                {

                }
            );
        };

        $scope.getDeviceTypeWithValue = function(id)
        {
            RestangularAppService.one('devicetypes', id).get().then(
                function(result)
                {
                    $scope.deviceTypeList = [ result.plain() ];

                    $scope.selectDeviceType(result);
                },
                function()
                {

                }
            );
        };

        $scope.getDeviceTypeById = function(id)
        {
            for (var i=0; i<$scope.deviceTypeList.length; i ++)
            {
                if ($scope.deviceTypeList[i].id == id)
                {
                    return $scope.deviceTypeList[i]
                }
            }

            return null;
        };

        $scope.initNew = function()
        {
            $scope.saleId = $state.params.saleId != 0 ? $state.params.saleId : null;
            $scope.customerId = $state.params.customerId != 0 ? $state.params.customerId : null;

            $scope.currentDevice = {
                'name' : 'Device Name',
                'passcode' : ' ',
                'serial' : ' ',
                'serial_type' : 'Hardware',
                'customer_id' : $state.customerId,
                'device_type_id' : 0
            };

            $scope.selectedDeviceType = null;
            $scope.getDeviceTypesWithParent(0);

            $scope.selectedDeviceRepair = null;
        };

        $scope.initWorkOrder = function()
        {
            $scope.saleId = $state.params.saleId;

            RestangularAppService.one('devices', $state.params.deviceId).get().then(
                function(result)
                {
                    $scope.currentDevice = result;

                    $scope.getDeviceTypeWithValue($scope.currentDevice.device_type_id);
                },
                function()
                {

                }
            );
        };

        $scope.initWarranty = function()
        {
            $scope.customerId = $state.params.customerId;
            $scope.warrantyId = $state.params.saleId;

            RestangularAppService.one('devices', $state.params.deviceId).get().then(
                function(result)
                {
                    $scope.currentDevice = result;

                    $scope.getDeviceTypeWithValue($scope.currentDevice.device_type_id);
                },
                function()
                {

                }
            );
        };

        $scope.action = $state.params.action;

        if ($scope.action == 'new')
        {
            $scope.initNew();
        }
        else if ($scope.action == 'workorder')
        {
            $scope.initWorkOrder();
        }
        else if ($scope.action == 'warranty')
        {
            $scope.initWarranty();
        }

        $scope.selectDeviceType = function(deviceType)
        {
            if ($scope.selectedDeviceType != null)
            {
                return;
            }

            if (!deviceType.pos_selectable)
            {
                $scope.getDeviceTypesWithParent(deviceType.id);
            }
            else
            {
                $scope.currentDevice.device_type_id = deviceType.id;
                $scope.selectedDeviceType = deviceType;
                $scope.deviceTypeList = [ deviceType ];

                $scope.checklistList = RestangularAppService.all('devicechecklists').getList({ device_type_id: deviceType.id }).$object;
                $scope.itemChecklistList = deviceType.device_item_checklist;

                var query = { all: 1, filter: { device_type_id: deviceType.id } };

                $scope.deviceRepairList = RestangularAppService.all('devicerepairs').getList(flattenParams(query)).$object;
            }

            $scope.deviceBreadCrumbList.push(deviceType);

            $scope.selectedDeviceRepair = null;
        };

        $scope.selectDeviceRepair = function(deviceRepair)
        {
            $scope.deviceRepairList = [ deviceRepair ];
            $scope.selectedDeviceRepair = deviceRepair;

            RestangularAppService.one('devicerepairs', deviceRepair.id).all('items').getList().then(
                function(data)
                {
                    console.log('data', data.plain());

                    var optionList = {};

                    for (var i=0; i<data.length; i++)
                    {
                        var option = {
                            value: data[i].option_value,
                            item_id: data[i].item_id
                        };

                        if (typeof optionList[data[i].device_repair_option.name] === 'undefined')
                        {
                            optionList[data[i].device_repair_option.name] = [];
                        }

                        optionList[data[i].device_repair_option.name].push(option);
                    }

                    $scope.deviceRepairOptionList = [];

                    var optionListKeys = Object.keys(optionList);

                    for (i=0; i<optionListKeys.length; i++)
                    {
                        var deviceRepairOption = {
                            name: optionListKeys[i],
                            values: optionList[optionListKeys[i]]
                        };

                        $scope.deviceRepairOptionList.push(deviceRepairOption);
                    }
                },
                function()
                {

                }
            )
        };

        $scope.addDevice = function()
        {
            RestangularAppService.all('devices').post($scope.currentDevice).then(
                function(result)
                {
                    $scope.currentDevice = result;
                    $scope.action = 'sale';

                    $scope.createNewSale();
                },
                function(data)
                {
                    NotifierService.error('Could not save ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.createNewSale = function()
        {
            if ($scope.saleId != null)
            {
                $scope.action = 'workorder';

                $scope.createNewWorkOrder();

                return;
            }

            var sale = {
                'customer_id' : $state.params.customerId ? $state.params.customerId : 0
            };

            RestangularAppService.all('sales').post(sale).then(
                function(result)
                {
                    $scope.saleId = result.id;
                    $scope.action = 'workorder';

                    $scope.createNewWorkOrder();
                },
                function(data)
                {
                    NotifierService.error('Could not create sale: ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.createNewWorkOrder = function()
        {
            var checklistListJson = JSON.stringify($scope.checklistList);
            var itemChecklistListJson = JSON.stringify($scope.itemChecklistList);

            var workOrder = {
                'device_id' : $scope.currentDevice.id,
                'workorder_status_id' : 1, // todo move to backend
                'next_update' : (Date.now() + 3600000) / 1000,
                'notes' : $scope.notes.initialCondition,
                'sale_id' : $scope.saleId,
                'quickdiag' : checklistListJson,
                'itemswithdevice' : itemChecklistListJson,
                'rating' : $scope.notes.deviceRating
            };

            RestangularAppService.all('workorders').post(workOrder).then(
                function()
                {
                    $state.transitionTo(
                        'pos.checkout',
                        {
                            'saleId' : $scope.saleId
                        }
                    );
                },
                function(data)
                {
                    NotifierService.error('Could not create workorder: ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.posWorkOrder = function()
        {
            var checklistListJson = JSON.stringify($scope.checklistList);
            var itemChecklistListJson = JSON.stringify($scope.itemChecklistList);

            var itemIdList = [];

            if (typeof $scope.deviceRepairOptionList !== 'undefined')
            {
                for (var i=0; i<$scope.deviceRepairOptionList.length; i++)
                {
                    var deviceRepairOption = $scope.deviceRepairOptionList[i];

                    if (deviceRepairOption.selected)
                    {
                        itemIdList.push(deviceRepairOption.selected);
                    }
                }
            }

            var itemIdListJson = JSON.stringify(itemIdList);

            var workOrder = {
                workorder_status_id: 1, // todo move to backend
                next_update: (Date.now() + 3600000) / 1000,
                notes: $scope.notes.initialCondition,
                sale_id: $scope.saleId,
                quickdiag: checklistListJson,
                itemswithdevice: itemChecklistListJson,
                rating: $scope.notes.deviceRating,
                item_id_list: itemIdListJson
            };

            if ($scope.currentDevice.id)
            {
                workOrder.device_id = $scope.currentDevice.id;
            }
            else
            {
                workOrder = angular.extend(workOrder, {
                    customer_id: $scope.customerId,
                    device_name: $scope.currentDevice.name,
                    device_passcode: $scope.currentDevice.passcode,
                    device_serial: $scope.currentDevice.serial,
                    device_serial_type: $scope.currentDevice.serial_type,
                    device_type_id: $scope.currentDevice.device_type_id
                });
            }

            if ($scope.saleId)
            {
                workOrder.sale_id = $scope.saleId
            }
            else
            {
                workOrder = angular.extend(workOrder, {
                    customer_id: $scope.customerId
                });
            }

            RestangularAppService.all('pos').all('workorder').post(workOrder).then(
                function(result)
                {
                    $state.transitionTo(
                        'pos.checkout',
                        {
                            'saleId' : result.sale_id
                        }
                    );
                },
                function(data)
                {
                    NotifierService.error('Could not create workorder: ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.createWarranty = function()
        {
            var checklistListJson = JSON.stringify($scope.checklistList);
            var itemChecklistListJson = JSON.stringify($scope.itemChecklistList);

            var workOrder = {
                next_update: (Date.now() + 3600000) / 1000,
                notes: $scope.notes.initialCondition,
                quickdiag: checklistListJson,
                itemswithdevice: itemChecklistListJson,
                rating: $scope.notes.deviceRating,
                warranty_workorder_id: $scope.warrantyId
            };

            RestangularAppService.all('pos').all('warranty').post(workOrder).then(
                function(result)
                {
                    $state.transitionTo(
                        'pos.checkout',
                        {
                            'saleId' : result.sale_id
                        }
                    );
                },
                function()
                {

                }
            )
        };
    }
);
