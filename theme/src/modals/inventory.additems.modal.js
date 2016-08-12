'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'InventoryAddItemsModalController',
    function($scope, $modalInstance, RestangularAppService, NotifierService)
    {
        $scope.missingItemsList = [];

        $scope.deviceTypeList = RestangularAppService.all('devicetypes').getList({all: 1}).$object;

        $scope.findById = function(arr, id)
        {
            for (var i=0; i<arr.length; i++)
            {
                if (arr[i].id == id)
                {
                    return arr[i];
                }
            }

            return null;
        };

        $scope.init = function()
        {
        };

        $scope.init();

        $scope.hasSearched = false;
        $scope.state = 'search';
        $scope.search = { query : '' };

        $scope.searchMissingItems = function()
        {
            if ($scope.search.query.length > 0)
            {
                RestangularAppService.all('missingitems/' + $scope.search.query).getList().then(
                    function(data)
                    {
                        $scope.missingItemsList = data.plain();

                        $scope.state = 'adding';
                    }
                );
            }

            $scope.hasSearched = true;
        };

        $scope.addMissingItem = function(item)
        {
            console.log(item);

            RestangularAppService.all('storeitems').post({item_id : item.id}).then(
                function()
                {
                    var i = $scope.missingItemsList.indexOf(item);
                    $scope.missingItemsList.splice(i, 1);
                },
                function()
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.finished = function()
        {
            $modalInstance.close({});
        };

        $scope.createNewInventoryItem = function()
        {
            $scope.newInventoryItem = {
                name : '',
                device_type_id : 1,
                global : 0,
                item_number : '0'
            };

            $scope.dirty = false;
            $scope.state = 'newitem';
        };

        $scope.dirty = false;
        $scope.makeDirty = function()
        {
            $scope.dirty = true;
        };

        $scope.saveNewInventoryItem = function()
        {
            document.getElementById('save-button').disabled = true;

            RestangularAppService.all('items').post($scope.newInventoryItem).then(
                function()
                {
                    $scope.finished();
                },
                function()
                {
                    document.getElementById('save-button').disabled = false;
                }
            );
        };
    }
);