'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'expenseController',
    function ($scope, ngTableParams, RestangularAppService, NotifierService, $state, $location)
    {
        $scope.options = [
            {label: 'Inventory', value: '1'},
            {label: 'Marketing', value: '2'},
            {label: 'Store Supplies', value: "3"},
            {label: 'Postage & Delivery', value: "4"},
            {label: 'Repairs & Maintenance', value: "5"},
            {label: 'Other', value: "6"}
        ];

        $scope.tableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                        date: 'asc'
                    }
                },
                $location.search()
            ), {
                total: 0,
                getData: function($defer, params)
                {
                    RestangularAppService.all('accountexpenses').getList(params.url()).then(
                        function (result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});

                            $scope.totals = 0;
                            $scope.totalInventory = 0;
                            $scope.totalMarketing = 0;
                            $scope.totalSupples = 0;
                            $scope.totalPostage = 0;
                            $scope.totalRepairs = 0;
                            $scope.totalOther = 0;

                            for (var i = result.length - 1; i >= 0; i--)
                            {
                                $scope.totals += parseFloat(result[i].amount);

                                switch (result[i].account_expense_category.id)
                                {
                                    case 1:
                                        $scope.totalInventory += parseFloat(result[i].amount);
                                        break;
                                    case 2:
                                        $scope.totalMarketing += parseFloat(result[i].amount);
                                        break;
                                    case 3:
                                        $scope.totalSupples += parseFloat(result[i].amount);
                                        break;
                                    case 4:
                                        $scope.totalPostage += parseFloat(result[i].amount);
                                        break;
                                    case 5:
                                        $scope.totalRepairs += parseFloat(result[i].amount);
                                        break;
                                    case 6:
                                        $scope.totalOther += parseFloat(result[i].amount);
                                        break;
                                }
                            }

                            $scope.totals = parseFloat($scope.totals.toFixed(2));
                            $defer.resolve(result);
                        },
                        function ()
                        {
                            NotifierService.error('Expense Report could not be loaded');
                        }
                    );
                }
            }
        );

        $scope.addPurchase = function(e)
        {
            var data = new FormData();

            data.append('amount', e.amount);
            data.append('vendor_id', e.vendor_id);
            data.append('account_expense_category_id', e.account_expense_category_id);
            data.append('comments', e.comments);
            data.append('file', document.getElementById('file').files[0]);

            RestangularAppService.all('accountexpenses').withHttpConfig({transformRequest: angular.identity}).post(data, {}, {'Content-Type': undefined}).then(
                function(data)
                {
                    $scope.expense = {};
                    $scope.tableParams.reload();
                },
                function(data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.deleteExpense = function(e)
        {
            RestangularAppService.one('accountexpenses', e.id).remove().then(
                function(data)
                {
                    $scope.tableParams.reload();
                }
            );
        }
    }
);