angular.module('theme.controllers')
    .controller('StoreSelectorController', function ($scope, $filter, StoreService) {
        $scope.stores = StoreService.stores();
        $scope.current = StoreService.current;
        $scope.selectStore = function (id) {
            StoreService.change(id);
        }
    });