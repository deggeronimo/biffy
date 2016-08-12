'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'AppointmentConfigController',
    function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService)
    {
        $scope.timeSlots = [
            { 'name' : '10:00 am', 'value' : 20, 'checked' : true, 'disabled' : false },
            { 'name' : '10:30 am', 'value' : 21, 'checked' : true, 'disabled' : false },
            { 'name' : '11:00 am', 'value' : 22, 'checked' : true, 'disabled' : false },
            { 'name' : '11:30 am', 'value' : 23, 'checked' : true, 'disabled' : false },
            { 'name' : '12:00 pm', 'value' : 24, 'checked' : true, 'disabled' : false },
            { 'name' : '12:30 pm', 'value' : 25, 'checked' : true, 'disabled' : false },
            { 'name' : '1:00 pm', 'value' : 26, 'checked' : true, 'disabled' : false },
            { 'name' : '1:30 pm', 'value' : 27, 'checked' : true, 'disabled' : false },
            { 'name' : '2:00 pm', 'value' : 28, 'checked' : true, 'disabled' : false },
            { 'name' : '2:30 pm', 'value' : 29, 'checked' : true, 'disabled' : false },
            { 'name' : '3:00 pm', 'value' : 30, 'checked' : true, 'disabled' : false },
            { 'name' : '3:30 pm', 'value' : 31, 'checked' : true, 'disabled' : false },
            { 'name' : '4:00 pm', 'value' : 32, 'checked' : true, 'disabled' : false },
            { 'name' : '4:30 pm', 'value' : 33, 'checked' : true, 'disabled' : false },
            { 'name' : '5:00 pm', 'value' : 34, 'checked' : true, 'disabled' : false },
            { 'name' : '5:30 pm', 'value' : 35, 'checked' : true, 'disabled' : false },
            { 'name' : '6:00 pm', 'value' : 36, 'checked' : true, 'disabled' : false },
            { 'name' : '6:30 pm', 'value' : 37, 'checked' : true, 'disabled' : false }
        ];

        $scope.findByValue = function(arr, value)
        {
            for (var i=0; i<arr.length; i++)
            {
                if (arr[i].value == value)
                {
                    return arr[i];
                }
            }

            return null;
        };

        $scope.checkTimeSlot = function(value)
        {
            if (isUndefined($scope.blackoutList))
            {
                return;
            }

            var timeSlot = $scope.findByValue($scope.timeSlots, value);

            timeSlot.checked = timeSlot.checked === false;

            for (var i=0; i<$scope.blackoutList.length; i++)
            {
                var blackout = $scope.blackoutList[i];

                if (blackout.hour_of_day == value)
                {
                    blackout.checked = timeSlot.checked;

                    return;
                }
            }

            $scope.blackoutList.push(
                {
                    day_of_week : null,
                    day_of_year : null,
                    hour_of_day : value,
                    year : null,
                    checked : false
                }
            );
        };

        $scope.dayOfWeekSlots = [
            { 'name' : 'Sunday', 'value' : 0, 'checked' : true, 'disabled' : false },
            { 'name' : 'Monday', 'value' : 1, 'checked' : true, 'disabled' : false },
            { 'name' : 'Tuesday', 'value' : 2, 'checked' : true, 'disabled' : false },
            { 'name' : 'Wednesday', 'value' : 3, 'checked' : true, 'disabled' : false },
            { 'name' : 'Thursday', 'value' : 4, 'checked' : true, 'disabled' : false },
            { 'name' : 'Friday', 'value' : 5, 'checked' : true, 'disabled' : false },
            { 'name' : 'Saturday', 'value' : 6, 'checked' : true, 'disabled' : false }
        ];

        $scope.checkDayOfWeekSlot = function(value)
        {
            if (isUndefined($scope.blackoutList))
            {
                return;
            }

            var dayOfWeekSlot = $scope.findByValue($scope.dayOfWeekSlots, value);

            dayOfWeekSlot.checked = dayOfWeekSlot.checked === false;

            for (var i=0; i<$scope.blackoutList.length; i++)
            {
                var blackout = $scope.blackoutList[i];

                if (blackout.day_of_week == value)
                {
                    blackout.checked = dayOfWeekSlot.checked;

                    return;
                }
            }

            $scope.blackoutList.push(
                {
                    day_of_week : value,
                    day_of_year : null,
                    hour_of_day : null,
                    year : null,
                    checked : false
                }
            );
        };

        $scope.blackoutDateList = [];

        $scope.appointmentsPerDay = RestangularAppService.one('stores/1/config', 2).get().$object;

        RestangularAppService.all('appointmentblackouts').getList().then(
            function(result)
            {
                $scope.blackoutList = result;

                $scope.initializeBlackoutList();
            },
            function()
            {

            }
        );

        $scope.initializeBlackoutList = function()
        {
            var i, j;

            for (i=0; i<$scope.blackoutList.length; i++)
            {
                var blackout = $scope.blackoutList[i];

                if (blackout.year != null)
                {
                    $scope.blackoutDateList.push(blackout);
                }
                else {
                    for (j = 0; j < $scope.timeSlots.length; j++)
                    {
                        var timeSlot = $scope.timeSlots[j];

                        if (blackout.hour_of_day == timeSlot.value)
                        {
                            timeSlot.checked = false;

                            if (blackout.store_id == null)
                            {
                                timeSlot.disabled = true;
                            }
                        }
                    }

                    for (j = 0; j < $scope.dayOfWeekSlots.length; j++)
                    {
                        var dayOfWeekSlot = $scope.dayOfWeekSlots[j];

                        if (blackout.day_of_week == dayOfWeekSlot.value)
                        {
                            dayOfWeekSlot.checked = false;

                            if (blackout.store_id == null)
                            {
                                dayOfWeekSlot.disabled = true;
                            }
                        }
                    }
                }
            }
        };

        $scope.save = function()
        {
            RestangularAppService.one('stores/1/config', $scope.appointmentsPerDay.id).put({value:$scope.appointmentsPerDay.value});

            for (var i=0; i<$scope.blackoutList.length; i++)
            {
                var blackout = $scope.blackoutList[i];

                if (isDefined(blackout.checked))
                {
                    if (isUndefined(blackout.id))
                    {
                        if (blackout.checked == false)
                        {
                            RestangularAppService.all('appointmentblackouts').post(blackout);
                        }
                    }
                    else
                    {
                        if (blackout.checked == false)
                        {
                            blackout.put();
                        }
                        else
                        {
                            RestangularAppService.one('appointmentblackouts', blackout.id).remove();
                        }
                    }
                }
            }

            $state.transitionTo('appointments.list');
        };

        $scope.today = function()
        {
            $scope.dt = new Date();
        };

        $scope.today();

        $scope.minDate = new Date();

        $scope.disabled = function(date, mode)
        {
            return false;
        };

        $scope.months = [
            { 'name' : 'January', 'days' : 31 },
            { 'name' : 'February', 'days' : 28 },
            { 'name' : 'March', 'days' : 31 },
            { 'name' : 'April', 'days' : 30 },
            { 'name' : 'May', 'days' : 31 },
            { 'name' : 'June', 'days' : 30 },
            { 'name' : 'July', 'days' : 31 },
            { 'name' : 'August', 'days' : 31 },
            { 'name' : 'September', 'days' : 30 },
            { 'name' : 'October', 'days' : 31 },
            { 'name' : 'November', 'days' : 30 },
            { 'name' : 'December', 'days' : 31 }
        ];

        $scope.getDayOfYearString = function(dayOfYear, year)
        {
            var retVal;

            var i = 0;
            var month = $scope.months[i];

            if (year % 4 == 0)
            {
                $scope.months[1].days ++;
            }

            while (dayOfYear > month.days)
            {
                dayOfYear -= month.days;
                i++;
                month = $scope.months[i];

            }

            if (year % 4 == 0)
            {
                $scope.months[1].days --;
            }

            retVal = month.name + ' ' + dayOfYear + ', ' + year;

            return retVal;
        };

        $scope.$watch('dt',
            function()
            {
                if (isUndefined($scope.blackoutList))
                {
                    return;
                }

                var dayOfYear = Math.ceil(($scope.dt - new Date($scope.dt.getFullYear(), 0, 1)) / 86400000);

                for (var i=0; i<$scope.blackoutList.length; i++)
                {
                    var blackout = $scope.blackoutList[i];

                    if (blackout.day_of_year == dayOfYear && blackout.year == $scope.dt.getFullYear())
                    {
                        return;
                    }
                }

                blackout = {
                    store_id : 1,
                    day_of_week : null,
                    day_of_year : dayOfYear,
                    hour_of_day : null,
                    year : $scope.dt.getFullYear(),
                    checked : false
                };

                $scope.blackoutList.push(blackout);
                $scope.blackoutDateList.push(blackout);
            }
        );

        $scope.deleteBlackoutDate = function(blackoutDate)
        {
            blackoutDate.checked = true;

            var i = $scope.blackoutDateList.indexOf(blackoutDate);
            $scope.blackoutDateList.splice(i, 1);
        };
    }
);