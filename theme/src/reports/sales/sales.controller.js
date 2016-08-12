'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('ReportSalesController', function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService)
    {
        $scope.paymentTypes = [
            '', 'Cash', 'Credit', 'Gift Card', 'Adjustment Amount', 'Adjustment Discount', 'Adjustment Comp'
        ];

        $scope.sum = {
            adjustments : 0,
            payments : 0,
            profit : 0,
            subtotal : 0,
            taxes : 0
        };

        $scope.reportType = $state.params.reportType;

        $scope.filterReport = function(e)
        {
            var fromDate= new Date(e.fromDate + " 00:00").getTime()/1000;
            var toDate = new Date(e.toDate + " 00:00").getTime()/1000 + 86400;

            RestangularAppService.all('reports/sales/' + $scope.reportType + '/' + fromDate + '/' + toDate).getList().then(
                function(data)
                {
                    $scope.reportItems = data.plain();

                    for (var i=0;i<$scope.reportItems.length;i++)
                    {
                        var reportItem = $scope.reportItems[i];

                        reportItem.items_sold = reportItem.sale_items.length;

                        $scope.sum.subtotal += parseFloat(reportItem.subtotal);
                        $scope.sum.taxes += parseFloat(reportItem.taxes);
                        $scope.sum.payments += parseFloat(reportItem.payments);
                        $scope.sum.adjustments += parseFloat(reportItem.adjustments);
                        $scope.sum.profit += parseFloat(reportItem.profit);

                        for (var j=0; j<reportItem.work_orders.length; j++)
                        {
                            var workOrder = reportItem.work_orders[j];

                            reportItem.items_sold += workOrder.sale_items.length;
                        }
                    }
                },
                function(data)
                {
                }
            );
        };
    }
);
