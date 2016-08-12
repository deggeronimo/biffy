'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'CompaniesApprovalsController',
    function ($scope, $state, ngTableParams, $location, RestangularAppService, NotifierService)
    {
        $scope.companyId = $state.params.companyId;

        $scope.tableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                        name: 'asc'
                    }
                },
                $location.search()
            ), {
                total: 0,
                getData: function($defer, params)
                {
                    $location.search(params.url());

                    RestangularAppService.one('companies', $scope.companyId).all('saleapprovals').getList(params.url()).then(
                        function(result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});

                            for (var i=0; i<result.length; i++)
                            {
                                var approval = result[i];

                                approval.workOrderList = [];

                                if (approval.workorder)
                                {
                                    approval.workOrderList.push(approval.workorder);
                                }
                                else
                                {
                                    for (var j=0; j<approval.sale.work_orders.length; j++)
                                    {
                                        var workOrder = approval.sale.work_orders[j];

                                        approval.workOrderList.push(workOrder);
                                    }
                                }
                            }

                            $defer.resolve(result);
                        },
                        function()
                        {
                            NotifierService.error('Companies could not be loaded');
                        }
                    );
                }
            }
        );
    }
);
