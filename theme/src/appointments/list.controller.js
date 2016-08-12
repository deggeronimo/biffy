'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller('AppointmentListController',
    function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService, $interval, $modal)
    {
        $scope.appointmentStatuses = [
            '', 'Pending', 'Canceled'
        ];

        $scope.appointmentStatusRange = function()
        {
            var result = [];
            for (var i = 1; i < $scope.appointmentStatuses.length; i++)
            {
                result.push($scope.appointmentStatuses[i]);
            }
            return result;
        };

        $scope.setSelectedAppointmentStatusId = function(id)
        {
            $scope.selectedAppointmentStatusId = id;
        };

        $scope.selectedAppointmentStatusId = 0;
        $scope.$watch('selectedAppointmentStatusId',
            function()
            {
                if ($scope.currentAppointment != null && $scope.currentAppointment.appointment_status_id != $scope.selectedAppointmentStatusId)
                {
                    $scope.currentAppointment.appointment_status_id = $scope.selectedAppointmentStatusId;

                    RestangularAppService.one('appointments', $scope.currentAppointment.id).put({'appointment_status_id': $scope.selectedAppointmentStatusId});
                }
            }
        );

        $scope.findById = function(arr, id)
        {
            for (var i=0; i<arr.length; i++)
            {
                if (arr[i].id == id)
                {
                    return arr[i];
                }
            }

            return null;
        };

        $scope.initDates = function()
        {
            //This function initializes the current day of the current year.  This is used for leap day calculations
            //  in booking appointments when the current year is not a leap year, but the next year is.
            var d = new Date();

            $scope.currentYear = d.getFullYear();

            $scope.currentMonth = d.getMonth();
            //Account for the leap day
            $scope.dayOfYear = ( $scope.currentYear %4 == 0 && $scope.currentMonth ) >= 1 ? 1 : 0;

            for (var i=0; i<$scope.currentMonth; i++)
            {
                $scope.dayOfYear += $scope.months[i].days;
            }

            $scope.dayOfYear += d.getDate();
        };

        $scope.displayAppointment = function(id)
        {
            $scope.currentAppointment = $scope.findById($scope.appointmentList, id);
            $scope.currentCustomer = $scope.currentAppointment.customer;

            $scope.selectedAppointmentStatusId = parseInt($scope.currentAppointment.appointment_status_id);
        };

        $scope.openEditCustomerModal = function()
        {
            var modalInstance = $modal.open(
                {
                    templateUrl : 'src/modals/editcustomer.modal.html',
                    controller : 'EditCustomerModalController',
                    size : 'lg',
                    resolve : {
                        customerId : function()
                        {
                            return $scope.currentCustomer.id;
                        }
                    }
                }
            );

            modalInstance.result.then(
                function(data)
                {
                    $scope.currentCustomer = data;
                },
                function()
                {
                }
            );
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

                    RestangularAppService.all('appointments').getList(params.url()).then(
                        function(result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);

                            $scope.appointmentList = result.plain();
                        },
                        function()
                        {
                            NotifierService.error('Appointments could not be loaded');
                        }
                    );
                }
            }
        );

        $scope.config = function()
        {
            $state.transitionTo('appointments.config');
        };

        $scope.export = function()
        {
            ExportService.go('appointments', $location.search());
        };

        $scope.createSale = function(customerId)
        {
            $state.transitionTo('pos.device', {
                'action' : 'new',
                'customerId' : $scope.currentCustomer.id,
                'deviceId' : 0
            });
        };
    });
