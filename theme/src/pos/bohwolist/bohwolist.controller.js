'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'WoUiController',
    function($scope, $global, RestangularAppService, $q, $filter, $state, ngTableParams, $location, NotifierService, ExportService, StoreService, UserService, $interval)
    {
        $global.set('setMainBG', false);

        // todo get data differently (eliminate call if possible)
        $scope.userList = RestangularAppService.one('stores', StoreService.id()).all('users').getList().$object;

        $scope.columns = {
            work_order_id   : { name : 'Work Order #',    property : 'work_order_id', checked : '1' },
            sale_id         : { name : 'Sale',            property : 'sale_id', checked : '1' },
            created         : { name : 'Created',         property : 'created_at', checked : '1' },
            next_update     : { name : 'Next Update',     property : 'next_update', checked : '1' },
            action          : { name : 'Action',          property : '', checked : '1' },
            updated_at      : { name : 'Updated',         property : 'updated_at', checked : '1' },
            customer        : { name : 'Customer',        property : 'sale.customer.family_name', checked : '1' },
            phone           : { name : 'Phone',           property : 'sale.customer.phone', checked : '1' },
            email           : { name : 'Email',           property : 'sale.customer.email', checked : '1' },
            device_items    : { name : 'Device Items',    property : '', checked : '1' },
            total_due       : { name : 'Ticket Total',    property : '', checked : '1' },
            balance_due     : { name : 'Balance Due',     property : '', checked : '1' },
            payments        : { name : 'Amount Paid',     property : '', checked : '1' },
            device_name     : { name : 'Device Name',     property : 'device.name', checked : '1' },
            device_type     : { name : 'Device Type',     property : 'device.device_type.name', checked : '1' },
            device_serial   : { name : 'Device Serial',   property : 'device.serial', checked : '1' },
            trade_credit    : { name : 'Trade Credit',    property : '', checked : '1' },
            company         : { name : 'Company',         property : 'sale.customer.company.name', checked : '1' },
            last_updated_by : { name : 'Last Updated By', property : '', checked : '1' },
            status          : { name : 'Status',          property : 'device_status.name', checked : '1' },
            warranty        : { name : 'Warranty Repair', property : '', checked : '1' }
        };

        $scope.checkedBoxes = [];

        var filteredName = '';

        $scope.$watch(
            'filteredName',
            function(newValue,oldValue)
            {
                $scope.tableParams.filter()[newValue]='true';
                $scope.tableParams.filter()[oldValue]='';
            }
        );

        $scope.setFilter = function(filterBy)
        {
            $scope.filteredName = filterBy;
        };

        $scope.order = function(predicate, reverse)
        {
            var orderBy = $filter('orderBy');
            $scope.workorders = orderBy($scope.workorders, predicate, reverse);
        };

        $scope.workOrderRowOnClick = function(workOrder)
        {
            $state.transitionTo(
                'pos.woedit',
                {
                    'workOrderId' : workOrder.id
                }
            );
        };

        $scope.checkboxAllToggle = function()
        {
            var checkboxes = document.getElementsByName('checklist');

            var toggleSet = $scope.checkedBoxes.length != checkboxes.length;

            for (var i=0; i<checkboxes.length; i ++)
            {
                var checkbox = checkboxes[i];

                if (checkbox.checked != toggleSet)
                {
                    $scope.checkboxChecked(checkbox);
                }

                checkbox.checked = toggleSet;
            }
        };

        $scope.checkboxChecked = function(checkbox)
        {
            var index = $scope.checkedBoxes.indexOf(checkbox.id);

            if (index == -1)
            {
                $scope.checkedBoxes.push(checkbox.id);
            }
            else
            {
                $scope.checkedBoxes.splice(index, 1);
            }
        };

        $scope.checkboxCheckedEvent = function(event)
        {
            $scope.checkboxChecked(event.currentTarget);
            event.stopPropagation();
        };

        $scope.assignUserToCurrentWorkOrder = function(user)
        {
            var checkboxes = document.getElementsByName('checklist');

            var toggleSet = $scope.checkedBoxes.length != checkboxes.length;

            for (var i=0; i<checkboxes.length; i ++)
            {
                var checkbox = checkboxes[i];

                if (checkbox.checked != toggleSet)
                {
                    RestangularAppService.one('workorders', checkbox.id.split('-')[2]).put({assigned_to_user_id: user.id}).then(
                        function()
                        {
                        },
                        function()
                        {

                        }
                    );
                }
            }
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
            ),
            {
                total: 0,
                getData: function($defer, params)
                {
                    $location.search(params.url());
                    RestangularAppService.all('workorders').getList(params.url()).then(
                        function(result)
                        {
                            var repairQueueCount = '0';
                            var awaitingPartsCount = '0';
                            var orderPartsCount = '0';
                            var awaitingApprovalCount = '0';

                            for (var i = 0; i < result.length; i++)
                            {
                                switch (result[i].workorder_status_id)
                                {
                                    case 2:
                                        awaitingPartsCount ++;
                                        break;
                                    case 3:
                                        repairQueueCount ++;
                                        break;
                                    case 6:
                                        orderPartsCount ++;
                                        break;
                                    case 12:
                                        awaitingApprovalCount ++;
                                        break;
                                }
                            }

                            $scope.repairQueue = { title: 'Repair Queue', href: '', titleBarInfo: '1hrs', text: repairQueueCount, color: 'green', classes: 'fa fa-wrench' };
                            $scope.awaitingParts = { title: 'Parts on Order', href: '#', titleBarInfo: '', text: awaitingPartsCount, color: 'yellow', classes: 'fa fa-truck' };
                            $scope.needToOrderParts = { title: 'Needs Parts', href: '#', titleBarInfo: '', text: orderPartsCount, color: 'inverse', classes: 'fa fa-exclamation-circle' };
                            $scope.awaitingApproval = { title: 'Approvals', href: '#', titleBarInfo: '', text: awaitingApprovalCount, color: 'alizarin', classes: 'fa fa-check' };
                            $scope.appointments = { title: 'Appointments', href: '#', titleBarInfo: '', text: '0', color: 'primary', classes: 'fa fa-clock-o' };

                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        },
                        function()
                        {
                            NotifierService.error('Work Orders could not be loaded');
                        }
                    );
                }
            }
        );

        $scope.export = function()
        {
            ExportService.go('workorders', $location.search());
        };

        $scope.status = {
            isopen: false
        };

        $scope.toggleDropdown = function($event)
        {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.status.isopen = !$scope.status.isopen;
        };

        $scope.actions = {
            process: function(callback, begin, end)
            {
                var object = null;
                if (typeof begin === 'function')
                {
                    object = begin(callback, end);
                }
                else
                {
                    $scope.actions.processLoop(callback, end);
                }
            },
            processLoop: function(callback, end, object)
            {
                var checkBoxes = document.getElementsByName('checklist');

                for (var i=0; i<checkBoxes.length; i ++)
                {
                    var checkBox = checkBoxes[i];

                    if (checkBox.checked == true)
                    {
                        //The checkbox ids are of the format checkbox-workorder-{{data.id}} so when split by '-',
                        //  the second element contains the id of the workorder that has been checked.
                        var workOrderId = checkBox.id.split('-')[2];

                        callback(workOrderId, object);
                    }
                }

                if (typeof end === 'function')
                {
                    end();
                }
            },
            beginInvoice: function(callback, end)
            {
                RestangularAppService.all('invoices').post().then(
                    function(result)
                    {
                        $scope.actions.processLoop(callback, end, result.plain());
                    },
                    function()
                    {

                    }
                )
            },
            createInvoiceCallback: function(workOrderId, invoice)
            {
                RestangularAppService.one('invoices', invoice.id).put({workorder_id:workOrderId});
            },
            sendToSingleDayQueueCallback: function(workOrderId)
            {
                RestangularAppService.one('workorders', workOrderId).put({queue: 1});
            },
            sendToMultiDayQueueCallback: function(workOrderId)
            {
                RestangularAppService.one('workorders', workOrderId).put({queue: 2});
            }
        };

        var interval = null;
        $scope.startInterval = function () {
            interval = $interval(function () {
                $scope.tableParams.reload();
            }, 60000);
        };

        $scope.cancelInterval = function () {
            $interval.cancel(interval);
        };

        $scope.$on('$destroy', function () {
            $scope.cancelInterval();
        });

        $scope.autoRefresh = false;
        $scope.toggleAutoRefresh = function () {
            if ($scope.autoRefresh) {
                $scope.cancelInterval();
            } else {
                $scope.startInterval();
            }

            $scope.autoRefresh = !$scope.autoRefresh;
        };
        $scope.toggleAutoRefresh();
    }
);

