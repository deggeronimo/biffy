'use strict';

angular.module('biffyApp')
    .controller('StoreConfigListController', function ($q, $scope, RestangularAppService, NotifierService, $modal) {
        $scope.config = [];
        $scope.storeConfig = [];
        $scope.taxes = [];

        $q.all([
            RestangularAppService.all('config').getList(),
            RestangularAppService.all('store-config').customGET(),
            RestangularAppService.all('store-taxes').getList()
        ]).then(function (result) {
            $scope.config = result[0];
            $scope.storeConfig = result[1];
            $scope.taxes = result[2];
        });

        $scope.getStoreConfig = function (configId) {
            return _.findWhere($scope.storeConfig, {config_id: configId});
        };

        $scope.save = function () {
            RestangularAppService.all('store-config').customPOST({entries: $scope.storeConfig}).then(function (){
                NotifierService.success('Store config updated');
                // todo update config values in current store without reload
            });
        };

        $scope.taxModal = function (tax) {
            var modalInstance = $modal.open({
                templateUrl: 'src/store-config/edit-tax.modal.html',
                size: 'lg',
                resolve: {
                    tax: function () {
                        return tax;
                    }
                },
                controller: function ($scope, $modalInstance, RestangularAppService, tax) {
                    if (tax === 'new') {
                        $scope.tax = {active: 1};
                        $scope.mode = 'Add';
                    } else {
                        $scope.tax = angular.copy(tax);
                        $scope.tax.percentage *= 100;
                        $scope.mode = 'Edit';
                    }

                    $scope.save = function () {
                        $scope.tax.percentage /= 100;

                        var promise;
                        if (tax === 'new') {
                            promise = RestangularAppService.all('store-taxes').post($scope.tax);
                        } else {
                            promise = RestangularAppService.one('store-taxes', $scope.tax.id).customPUT($scope.tax);
                        }

                        promise.then(function () {
                            $modalInstance.close(true);
                        });
                    };

                    $scope.cancel = function () {
                        $modalInstance.close(false);
                    };
                }
            });

            modalInstance.result.then(function (added) {
                if (added) {
                    RestangularAppService.all('store-taxes').getList().then(function (taxes) {
                        $scope.taxes = taxes;
                    });
                }
            });
        };

        $scope.addTax = function () {
            $scope.taxModal('new');
        };

        $scope.editTax = function (tax) {
            $scope.taxModal(tax)
        }
    });