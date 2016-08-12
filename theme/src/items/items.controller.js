'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'itemsController',
    function ($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService, ExportService)
    {
        $scope.selectDeviceType = function(deviceType)
        {
            $scope.currentItem.device_type_id = deviceType.id;
            $scope.currentItem.device_type = deviceType.plain();
        };

        $scope.addItem = function()
        {
            $scope.isAdd = true;

            $scope.currentItem = {
                item_number: '',
                unit_price: 0,
                labor_cost: 0,
                distro_price: 0,
                required: 0,
                device_type_id: 0
            };
        };

        $scope.editItem = function(item)
        {
            $scope.isAdd = false;
            item.isEdit = true;
            $scope.currentItem = item;
        };

        $scope.removeItem = function(item)
        {
            RestangularAppService.one('items', item.id).remove().then(
                function()
                {
                    $scope.tableParams.reload();
                }
            );
        };

        $scope.saveItem = function(item)
        {
            RestangularAppService.all('items').post(item).then(
                function()
                {
                    $scope.isAdd = false;
                    $scope.tableParams.reload();
                }, function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.updateItem = function(item)
        {
            var putItem = {
                id: item.id,
                item_number: item.item_number,
                unit_price: item.unit_price,
                labor_cost: item.labor_cost,
                distro_price: item.distro_price,
                required: item.required,
                device_type_id: item.device_type_id ? item.device_type_id : 0
            };

            RestangularAppService.one('items', item.id).put(putItem).then(
                function()
                {
                    item.isEdit = false;
                }
            );
        };

        $scope.cancelItem = function(item)
        {
            item.isEdit = false;
        };

        $scope.tableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                        given_name: 'asc'
                    }
                },
                $location.search()
            ), {
                total: 0,
                getData: function ($defer, params)
                {
                    $location.search(params.url());
                    RestangularAppService.all('items').getList(params.url()).then(
                        function(result)
                        {
                            $scope.itemAdd = false;
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        }, function()
                        {
                            NotifierService.error('Items could not be loaded');
                        }
                    );
                }
            }
        );

        $scope.export = function () {
            ExportService.go('items', $location.search());
        };
    }
);