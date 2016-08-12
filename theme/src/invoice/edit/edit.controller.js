'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'InvoiceEditController',
    function($scope, $state, $stateParams, RestangularAppService, ngTableParams, $location, NotifierService, $global)
    {
        $global.set('setMainBG', true);

        $scope.id = $stateParams.id;
        $scope.data = {};
        $scope.mode = 'Loading';

        $scope.money = {
            value: data.total_due
        };

        $scope.isAdd = function()
        {
            return $scope.id === 'new';
        };

        $scope.isEdit = function()
        {
            return $scope.mode === 'Edit';
        };

        $scope.init = function()
        {
            RestangularAppService.one('invoices', $scope.id).get().then(
                function(data)
                {
                    $scope.mode = 'Edit';
                    $scope.data = data;

                    $scope.money = {
                        value: data.total_due
                    };

                    $scope.loadSales();
                },
                function(data)
                {
                    NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.close = function()
        {
            RestangularAppService.one('invoices', $scope.id).put({ closed: 1 }).then(
                function()
                {
                    $scope.init();
                }
            )
        };

        $scope.loadSales = function()
        {
            var query = {
                filter: {
                    company_invoicing: $scope.data.company_id,
                    customer_invoicing: $scope.data.customer_id
                }
            };

            RestangularAppService.all('sales').getList(flattenParams(query)).then(
                function(result)
                {
                    $scope.saleList = result.plain();
                },
                function()
                {

                }
            );
        };

        $scope.store = function()
        {
            var invoice = {
                company_id: $scope.data.company_id,
                customer_id: $scope.data.customer_id,
                details: $scope.data.details
            };

            RestangularAppService.all('invoices').post(invoice).then(
                function(result)
                {
                    $state.transitionTo(
                        'invoice.edit',
                        {
                            id : result.id
                        }
                    );
                },
                function()
                {

                }
            );
        };

        $scope.update = function()
        {
            $scope.data.put().then(
                function()
                {
                    $state.transitionTo('invoice');
                },
                function(data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.cancel = function()
        {
            $state.transitionTo('invoice');
        };

        $scope.addSaleToInvoice = function(sale)
        {
            RestangularAppService.one('invoices', $scope.id).put({ sale_id: sale.id }).then(
                function()
                {
                    $scope.loadSales();
                    $scope.tableParams.reload();
                },
                function()
                {

                }
            );
        };

        $scope.makePayment = function(paymentType)
        {
            var payment = {
                invoice_id: $scope.data.id,
                sale_payment_type_id: paymentType,
                amount: $scope.money.value
            };

            RestangularAppService.all('invoice-payments').post(payment).then(
                function()
                {
                    $scope.init();
                },
                function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.removeSaleFromInvoice = function(sale)
        {
            RestangularAppService.one('invoices', $scope.id).put({ remove_sale_id: sale.id }).then(
                function()
                {
                    $scope.loadSales();
                    $scope.tableParams.reload();
                },
                function()
                {

                }
            );
        };

        $scope.selectCompany = function(company)
        {
            $scope.data.company = company;
            $scope.data.company_id = company.id;
        };

        $scope.selectCustomer = function(customer)
        {
            $scope.data.customer = customer;
            $scope.data.customer_id = customer.id;

            $scope.data.customer.name = $scope.data.customer.given_name + ' ' + $scope.data.customer.family_name;
        };

        $scope.tableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                        given_name: 'asc'
                    }
                },
                $location.search()
            ), {
                total: 0,
                getData: function($defer, params)
                {
                    if ($scope.isAdd())
                    {
                        return;
                    }

                    $location.search(params.url());

                    RestangularAppService.one('invoices', $scope.id).all('sales').getList(params.url()).then(
                        function(result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        },
                        function()
                        {
                            NotifierService.error('Invoice Sales could not be loaded');
                        }
                    );
                }
            }
        );

        if($scope.isAdd())
        {
            $scope.mode = 'Add';
        }
        else
        {
            $scope.init();
        }
    }
);
