'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('ReportInventoryController', function($scope, $state, $controller)
    {
        console.log('Injecting ReportController');
        $controller(
            'ReportController',
            {
                reportName : 'inventory',
                reportType : $state.params.reportType,
                $scope : $scope
            }
        );

        $scope.ReloadCallback = function()
        {
            console.log($scope.reportItemList);
            console.log('Callback Successful');
        };
    }
);
