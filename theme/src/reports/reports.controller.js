'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('ReportController', function(reportName, reportType, $location, $scope, RestangularAppService)
    {
        $scope.reportItemList = [];
        $scope.reportName = reportName;
        $scope.reportType = reportType;

        $scope.getStartDate = function()
        {
            return 1420070400;
        };

        $scope.getEndDate = function()
        {
            return 1451606400;
        };

        $scope.ReloadCallback = function()
        {
            console.log($scope.reportItemList);
        };

        $scope.refreshData = function()
        {
            var startDate = $scope.getStartDate();
            var endDate = $scope.getEndDate();

            RestangularAppService.all('reports/' + $scope.reportName + '/' + $scope.reportType + '/' + startDate + '/' + endDate)
                .getList().then(
                function (result)
                {
                    $scope.reportItemList = result.plain();

                    $scope.ReloadCallback();
                },
                function (data)
                {
                }
            );
        };

        $scope.refreshData();

        console.log('ReportController loaded');
    }
);

angular.module('biffyApp').controller(
    'ReportHomeController',
    function($rootScope, $state, $scope)
    {
        $scope.reportList = [
            { name : 'Customers', state : 'reports.customers', uri : 'customers', graphical : true, summary : true, detailed : true },
            { name : 'Employees', state : 'reports.employees', uri : 'employees', graphical : true, summary : true, detailed : true },
            { name : 'Sales', state : 'reports.sales', uri : 'sales', graphical : true, summary : true, detailed : true },
            { name : 'Categories', state : 'reports.categories', uri : 'categories', graphical : true, summary : true, detailed : false },
            { name : 'Discounts', state : 'reports.discounts', uri : 'discounts', graphical : true, summary : true, detailed : false },
            { name : 'Items', state : 'reports.items', uri : 'items', graphical : true, summary : true, detailed : false },
            { name : 'Payments', state : 'reports.payments', uri : 'payments', graphical : true, summary : true, detailed : false },
            { name : 'Vendors', state : 'reports.vendors', uri : 'vendors', graphical : true, summary : true, detaield : true },
            { name : 'Taxes', state : 'reports.taxes', uri : 'taxes', graphical : true, summary : true, detailed : false },
            { name : 'Receivings', state : 'reports.receivings', graphical : false, summary : false, detailed : true },
            { name : 'Inventory', state : 'reports.inventory', graphical : false, summary : true, detailed : true },
            { name : 'Deleted Sales', state : 'reports.deletedsales', graphical : false, summary : false, detailed : true },
            { name : 'Gift Cards', state : 'reports.giftcards', graphical : false, summary : true, detailed : true }
        ];

        $scope.generate = {};

        $scope.generateReport = function()
        {
            console.log($scope.generate);
        };

        console.log($scope);
    }
);