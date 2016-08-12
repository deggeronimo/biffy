'use strict';

angular.module('biffyApp').controller(
    'CompaniesStoreItemsEditController',
    function($q, $scope, $stateParams, $state, RestangularAppService, NotifierService)
    {
        $scope.companyId = $stateParams.companyId;
        $scope.id = $stateParams.id || null;

        $scope.data = {};
        $scope.mode = 'Loading';

        $scope.$watch(
            'currentStoreItem',
            function(newValue)
            {
                $scope.data.store_item_id = newValue.id;
            }
        );

        $scope.isAdd = function()
        {
            return $scope.id === null;
        };

        $scope.isEdit = function()
        {
            return $scope.mode === 'Edit';
        };

        if($scope.isAdd())
        {
            $scope.mode = 'Add';
        }
        else
        {
            RestangularAppService.one('companies', $scope.companyId).one('storeitems', $scope.id).get().then(
                function(data)
                {
                    $scope.mode = 'Edit';
                    $scope.data = data;
                },
                function(data)
                {
                    NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
                }
            );
        }

        $scope.store = function()
        {
            RestangularAppService.one('companies', $scope.companyId).all('storeitems').post($scope.data).then(
                function()
                {
                    $state.transitionTo('companies.storeitems', $stateParams);
                },
                function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.update = function()
        {
            $scope.data.put().then(
                function()
                {
                    $state.transitionTo('companies.storeitems', $stateParams);
                },
                function(data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.destroy = function()
        {
            $scope.data.remove().then(
                function()
                {
                    $state.transitionTo('companies.storeitems', $stateParams);
                },
                function(data)
                {
                    NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.cancel = function()
        {
            $state.transitionTo('companies.storeitems', $stateParams);
        };
    }
);