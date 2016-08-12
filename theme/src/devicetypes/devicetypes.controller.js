'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'DeviceTypesController',
    function($scope, $location, ngTableParams, RestangularAppService, NotifierService, $modal)
    {
        $scope.languageList = RestangularAppService.all('languages').getList({all: 1}).$object;
        $scope.deviceManufacturerList = RestangularAppService.all('devicemanufacturers').getList({all: 1}).$object;
        $scope.deviceFamilyList = RestangularAppService.all('devicefamilies').getList({all: 1}).$object;
        $scope.deviceRepairOptions = RestangularAppService.all('devicerepairoptions').getList({all: 1}).$object;
        $scope.deviceRepairTemplateList = RestangularAppService.all('devicerepairtypes').getList({all: 1}).$object;

        $scope.trueFalse = [ { id: 0, name: 'False'}, { id: 1, name: 'True' } ];

        RestangularAppService.all('devicetypes').getList({all:'1'}).then(
            function(result)
            {
                $scope.deviceTypes = result.plain();
                $scope.deviceTypeOptions = [ { id: 0, name: 'Root' } ].concat($scope.deviceTypes);
            },
            function()
            {
            }
        );

        //The item search box requires this.
        $scope.data = {};

        $scope.currentDevice = {};
        $scope.currentRepair = {};
        $scope.currentRepairItem = {};

        $scope.reset = function()
        {
            $scope.tableParams.filter()['name'] = null;
            $scope.tableParams.filter()['parent_name'] = null;
            $scope.tableParams.filter()['manufacturer_id'] = null;
            $scope.tableParams.filter()['family_id'] = null;
        };

        $scope.selectDeviceType = function(deviceType)
        {
            $scope.currentDevice.parent_device_type_id = deviceType.id;
            $scope.currentDevice.parent_device_type = deviceType.plain();
        };

        $scope.selectManufacturer = function(manufacturer)
        {
            $scope.currentDevice.device_manufacturer_id = manufacturer.id;
            $scope.currentDevice.device_manufacturer = manufacturer.plain();
        };

        $scope.selectFamily = function(family)
        {
            $scope.currentDevice.device_family_id = family.id;
            $scope.currentDevice.device_family = family.plain();
        };

        $scope.addNewDevice = function()
        {
            $scope.currentDevice = {};

            $scope.createKeyNames($scope.currentDevice, 'device_type', true);
        };

        $scope.cancelDevice = function(data)
        {
            angular.copy(data.backup, data);
            data.backup = null;
            data.isEdit = false;
        };

        $scope.editDevice = function(data)
        {
            data.backup = angular.copy(data);
            data.isEdit = true;
        };

        $scope.selectDevice = function(data)
        {
            $scope.currentDevice = angular.copy(data);
            $scope.currentDeviceRepair = null;

            RestangularAppService.all('devicerepairs?filter[device_type_id]=' + $scope.currentDevice.id).getList().then(
                function(result)
                {
                    $scope.currentDevice.repairList = result;

                    $scope.createKeyNames($scope.currentDevice, 'device_type');

                    $scope.deviceRepairsTableParams.reload();
                },
                function()
                {

                }
            );
        };

        $scope.selectDeviceRepair = function(data)
        {
            $scope.currentDeviceRepair = angular.copy(data);

            RestangularAppService.all('devicerepairs?filter[device_type_id]=' + $scope.currentDevice.id).getList().then(
                function(result)
                {
                    $scope.currentDevice.repairList = result;

                    $scope.createKeyNames($scope.currentDeviceRepair, 'device_repair');

                    $scope.deviceRepairItemTableParams.reload();
                },
                function()
                {

                }
            );
        };

        $scope.selectDeviceRepairItem = function(data)
        {
            $scope.currentDeviceRepairItem = angular.copy(data);

            $scope.createKeyNames($scope.currentDeviceRepairItem, 'device_repair_option_item');
        };

        $scope.updateDevice = function(data)
        {
            RestangularAppService.one('devicetypes', data.id).put(data).then(
                function()
                {
                    data.backup = null;
                    data.isEdit = false;
                },
                function()
                {

                }
            )
        };

        $scope.modelAttributes = {
            device_type: [
                'name', 'meta_description', 'meta_keywords', 'web_description'
            ],
            device_repair: [
                'name', 'estimated_cost', 'meta_description', 'meta_keywords', 'web_description'
            ],
            device_repair_option_item: [
                'estimated_cost'
            ]
        };

        $scope.createKeyNames = function(base, model, isNew)
        {
            isNew = isNew ? isNew : false;

            base.attributes = {};
            base.languageKeySet = [];

            var languageKeyBase = model + '_' + base.id + '_';

            var modelAttributes = $scope.modelAttributes[model];

            for (var i=0; i<modelAttributes.length; i++)
            {
                var currentValue = modelAttributes[i];

                base.attributes[currentValue] = {};

                var key = languageKeyBase + currentValue;

                if (isNew)
                {
                    var languageKey =
                    {
                        key: key, values: []
                    };

                    base.languageKeySet.push(languageKey);

                    base.attributes[currentValue] = [];
                }
                else
                {
                    (function (base, key, currentValue)
                    {
                        $scope.nameAttributes = RestangularAppService.one('languagekeys', key).all('strings').getList().then(
                            function(data)
                            {
                                data = data.plain();

                                var languageKey =
                                {
                                    key: key, values: data
                                };

                                base.languageKeySet.push(languageKey);

                                base.attributes[currentValue] = data;
                            },
                            function()
                            {
                            }
                        )
                    })(base, key, currentValue);
                }
            }
        };

        $scope.saveLanguageKeySet = function(model, callback)
        {
            var languageKeySet = model.languageKeySet;

            var finished = 0;

            languageKeySet.forEach(
                function(element, index, array)
                {
                    RestangularAppService.one('languagekeys', element.key).all('strings').getList().then(
                        function(data)
                        {
                            for (var i=0; i<$scope.languageList.length; i++)
                            {
                                if (element.values[i])
                                {
                                    element.values[i].id = data[i].id;
                                }
                                else
                                {
                                    element.values[i] = { id: data[i].id }
                                }
                            }

                            RestangularAppService.one('languagekeys', element.key).one('strings', model.id).customPUT({strings:element.values}).then(
                                function()
                                {
                                    finished ++;

                                    if (finished == languageKeySet.length)
                                    {
                                        callback();
                                    }
                                },
                                function()
                                {
                                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                                }
                            );
                        },
                        function()
                        {
                            NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                        }
                    );
                }
            );
        };

        $scope.cancelDeviceRepair = function(deviceRepair)
        {
            angular.copy(deviceRepair.backup, deviceRepair);
            deviceRepair.backup = null;
            deviceRepair.isEdit = false;
        };

        $scope.cancelDeviceRepairItem = function(deviceRepairItem)
        {
            angular.copy(deviceRepairItem.backup, deviceRepairItem);
            deviceRepairItem.backup = null;
            deviceRepairItem.isEdit = false;
        };

        $scope.cancelNewDeviceRepairItem = function()
        {
            $scope.newDeviceRepairItem = null;
        };

        $scope.createNewDeviceRepair = function()
        {
            var modalInstance = $modal.open(
                {
                    templateUrl : 'src/modals/devicetypes/create.repair.template.html',
                    controller : 'EditDeviceTypeRepairModalController',
                    size : 'lg',
                    resolve : {
                        deviceTypeId  : function()
                        {
                            return $scope.currentDevice.id;
                        },
                        deviceTypeRepairId : function()
                        {
                            return 'new';
                        }
                    }
                }
            );

            modalInstance.result.then(
                function()
                {
                    $scope.deviceRepairsTableParams.reload();
                },
                function()
                {
                    $scope.deviceRepairsTableParams.reload();
                }
            );
        };

        $scope.createNewDeviceRepairItem = function()
        {
            $scope.newDeviceRepairItem = {};
        };

        $scope.deleteDevice = function(device)
        {
            RestangularAppService.one('devicetypes', device.id).remove().then(
                function()
                {
                    $scope.tableParams.reload();
                },
                function()
                {

                }
            );
        };

        $scope.deleteDeviceRepair = function(deviceRepair)
        {
            RestangularAppService.one('devicerepairs', deviceRepair.id).remove().then(
                function()
                {
                    $scope.deviceRepairsTableParams.reload();
                },
                function()
                {

                }
            );
        };

        $scope.destroyCurrentDevice = function()
        {
            RestangularAppService.one('devicetypes', $scope.currentDevice.id).remove().then(
                function()
                {
                    $scope.currentDevice = null;
                    $scope.currentDeviceRepair = null;

                    $scope.tableParams.reload();
                },
                function()
                {

                }
            );
        };

        $scope.destroyCurrentDeviceRepair = function()
        {
            $scope.currentDeviceRepair.remove().then(
                function()
                {
                    $scope.currentDeviceRepair = null;

                    $scope.deviceRepairsTableParams.reload();
                },
                function()
                {

                }
            );
        };

        $scope.deleteDeviceRepairItem = function(deviceRepairItem)
        {
            deviceRepairItem.remove().then(
                function()
                {
                    $scope.deviceRepairItemTableParams.reload();
                }
            )
        };

        $scope.editDeviceRepair = function(deviceRepair)
        {
            deviceRepair.backup = angular.copy(deviceRepair);
            deviceRepair.isEdit = true;
        };

        $scope.editDeviceRepairItem = function(deviceRepairItem)
        {
            deviceRepairItem.backup = angular.copy(deviceRepairItem);
            deviceRepairItem.isEdit = true;
        };

        $scope.storeNewDeviceRepairItem = function()
        {
            $scope.newDeviceRepairItem.item_id = $scope.data.item_id;

            RestangularAppService.one('devicerepairs', $scope.currentDeviceRepair.id).all('items').post($scope.newDeviceRepairItem).then(
                function()
                {
                    $scope.newDeviceRepairItem = null;
                    $scope.deviceRepairItemTableParams.reload();
                },
                function()
                {

                }
            )
        };

        $scope.storeCurrentDevice = function(makeNew)
        {
            makeNew = makeNew ? makeNew : false;

            RestangularAppService.all('devicetypes').post($scope.currentDevice).then(
                function(result)
                {
                    if (makeNew)
                    {
                        $scope.addNewDevice();
                    }
                    else
                    {
                        $scope.selectDevice(result);
                    }
                },
                function()
                {

                }
            )
        };

        $scope.setCurrentDeviceParentNull = function()
        {
            $scope.currentDevice.parent_device_type_id = 0;
            $scope.currentDevice.parent_device_type.name = 'Null';
        };

        $scope.setCurrentDeviceManufacturerNull = function()
        {
            $scope.currentDevice.device_manufacturer_id = 0;
            $scope.currentDevice.device_manufacturer.name = 'Null';
        };

        $scope.setCurrentDeviceFamilyNull = function()
        {
            $scope.currentDevice.device_family_id = 0;
            $scope.currentDevice.device_family.name = 'Null';
        };

        $scope.updateCurrentDevice = function()
        {
            var currentDevice = $scope.currentDevice;

            var device = {
                parent_device_type_id: currentDevice.parent_is_null ? 0 : currentDevice.parent_device_type_id,
                device_manufacturer_id: currentDevice.device_manufacturer_id,
                device_family_id: currentDevice.device_family_id,
                filters: currentDevice.filters,
                image: currentDevice.image,
                sort_order: currentDevice.sort_order,
                model: currentDevice.model,
                release_date: currentDevice.release_date
            };

            RestangularAppService.one('devicetypes', currentDevice.id).put(device).then(
                function()
                {
                    $scope.saveLanguageKeySet(
                        currentDevice,
                        function()
                        {
                            $scope.tableParams.reload();
                        }
                    );
                },
                function()
                {
                }
            )
        };

        $scope.updateCurrentDeviceRepair = function()
        {
            var deviceRepair = {
                image: $scope.currentDeviceRepair.image,
                sort_order: $scope.currentDeviceRepair.sort_order,
                status: $scope.currentDeviceRepair.status
            };

            RestangularAppService.one('devicerepairs', $scope.currentDeviceRepair.id).put(deviceRepair).then(
                function()
                {
                    $scope.saveLanguageKeySet(
                        $scope.currentDeviceRepair,
                        function()
                        {
                            $scope.deviceRepairsTableParams.reload();
                        }
                    );
                },
                function()
                {

                }
            )
        };

        $scope.updateCurrentDeviceRepairItem = function()
        {
            var deviceRepair = {
                image: $scope.currentDeviceRepairItem.image,
                sort_order: $scope.currentDeviceRepairItem.sort_order,
                status: $scope.currentDeviceRepairItem.status
            };

            RestangularAppService.one('devicerepairs', $scope.currentDeviceRepair.id).one('items', $scope.currentDeviceRepairItem.id).put(deviceRepair).then(
                function()
                {
                    $scope.saveLanguageKeySet(
                        $scope.currentDeviceRepairItem,
                        function()
                        {
                            $scope.deviceRepairItemTableParams.reload();
                        }
                    );
                },
                function()
                {

                }
            )
        };

        $scope.updateDeviceRepair = function(deviceRepair)
        {
            RestangularAppService.one('devicerepairs', deviceRepair.id).put({item_id: deviceRepair.item_id, device_repair_type_id: deviceRepair.device_repair_type_id}).then(
                function()
                {
                    deviceRepair.device_repair_type = RestangularAppService.one('devicerepairtypes', deviceRepair.device_repair_type_id).get().$object;

                    deviceRepair.backup = null;
                    deviceRepair.isEdit = false;
                },
                function()
                {

                }
            )
        };

        $scope.updateDeviceRepairItem = function(deviceRepairItem)
        {
            deviceRepairItem.put().then(
                function()
                {
                    deviceRepairItem.backup = null;
                    deviceRepairItem.isEdit = false;
                },
                function()
                {

                }
            )
        };

        $scope.tableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                        name: 'asc'
                    }
                },
                $location.search()
            ),
            {
                total: 0,
                getData: function($defer, params)
                {
                    $location.search(params.url());

                    RestangularAppService.all('devicetypes').getList(params.url()).then(
                        function(result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result.plain());
                        }, function()
                        {
                            NotifierService.error('Device Types could not be loaded');
                        }
                    );
                }
            }
        );

        $scope.deviceRepairsTableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                        name: 'asc'
                    }
                },
                $location.search()
            ),
            {
                total: 0,
                getData: function($defer)
                {
                    RestangularAppService.all('devicerepairs?filter[device_type_id]=' + $scope.currentDevice.id).getList({all:1}).then(
                        function(result)
                        {
                            $defer.resolve(result);
                        },
                        function()
                        {

                        }
                    );
                }
            }
        );

        $scope.deviceRepairItemTableParams = new ngTableParams(
            angular.extend(
                {
                    page : 1,
                    count : 10,
                    sorting:
                    {
                        name : 'asc'
                    }
                },
                $location.search()
            ),
            {
                total: 0,
                getData: function($defer)
                {
                    RestangularAppService.one('devicerepairs', $scope.currentDeviceRepair.id).all('items').getList().then(
                        function(result)
                        {
                            $defer.resolve(result);
                        },
                        function()
                        {

                        }
                    );
                }
            }
        );
    }
);
