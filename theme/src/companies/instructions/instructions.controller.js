'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'CompaniesInstructionsController',
    function ($scope, $state, RestangularAppService, NotifierService)
    {
        $scope.companyId = $state.params.companyId;

        RestangularAppService.one('companies', $scope.companyId).all('instructions?filter[company_id]=' + $scope.companyId).getList().then(
            function(data)
            {
                $scope.data = data[0].plain();
                $scope.data.lock_trade_credit = $scope.data.lock_trade_credit ? '1' : '0';
            },
            function(data)
            {
                NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
            }
        );

        $scope.toggleLockTradeCredit = function()
        {
            $scope.data.lock_trade_credit = $scope.data.lock_trade_credit == '0' ? '1' : '0';
        };

        $scope.cancel = function()
        {
            $state.transitionTo('companies');
        };

        $scope.update = function()
        {
            console.log($scope.data);
            RestangularAppService.one('companies', $scope.companyId).one('instructions', $scope.data.id).put($scope.data).then(
                function()
                {
                    $state.transitionTo('companies');
                },
                function(data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };
    }
);
