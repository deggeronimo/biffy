'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'InventoryController',
    function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService, $timeout, $modal, $global)
    {
        $scope.$watch(
            'itemQuery',
            function(v)
            {
                $scope.tableParams.filter()['search'] = v;
            }
        );

        $scope.tableParams = new ngTableParams(
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
                    RestangularAppService.all('storeitems').getList(params.url()).then(
                        function(result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        }, function()
                        {
                            NotifierService.error('Store Items could not be loaded');
                        }
                    );
                }
            }
        );

        $scope.cancelStoreItem = function(data)
        {
            angular.copy(data.backup, data);

            data.backup = null;
            data.isEdit = false;
        };

        $scope.editStoreItem = function(data)
        {
            data.backup = angular.copy(data);
            data.isEdit = true;
        };

        $scope.updateStoreItem = function(data)
        {
            data.put().then(
                function()
                {
                    data.backup = null;
                    data.isEdit = false;
                },
                function()
                {
                }
            );
        };

        $scope.addMissingItems = function()
        {
            var modalInstance = $modal.open(
                {
                    templateUrl : 'src/modals/inventory.additems.modal.html',
                    controller : 'InventoryAddItemsModalController',
                    size : 'lg'
                }
            );

            modalInstance.result.then(
                function(data)
                {
                    $scope.tableParams.reload();
                },
                function()
                {
                }
            );
        };
    }
);
