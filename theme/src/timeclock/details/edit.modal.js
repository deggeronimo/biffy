'use strict';

angular.module('biffyApp')
    .controller('TimeclockDetailsEditModalController', function ($scope, $modalInstance, DateRange, RestangularAppService, entry, editing) {
        $scope.editing = editing;
        $scope.start = $scope.editing ? DateRange.startFromTimestampUtc({from: entry.time_in, to: entry.time_out}) : DateRange.startFromTimestamp({from: entry.time_in, to: entry.time_out});
        $scope.range = DateRange.rangeWithTime($scope.start);
        $scope.dateRangePickerOptions = DateRange.pickerOptionsWithTime($scope.start);

        $scope.save = function () {
            if (typeof $scope.range.from === 'object') {
                var vals = DateRange.momentToDatetime($scope.range);
                var promise;

                if ($scope.editing) {
                    promise = RestangularAppService.one('timeclock/edit', entry.id).customPUT({
                        time_in: vals.from,
                        time_out: vals.to
                    });
                } else {
                    promise = RestangularAppService.all('timeclock/add').customPOST({
                        user_id: entry.user_id,
                        time_in: vals.from,
                        time_out: vals.to,
                        clock_type: entry.clock_type
                    });
                }

                promise.then(function () {
                    $modalInstance.close();
                });
            } else {
                $modalInstance.close();
            }
        };

        $scope.deleteEntry = function () {
            if (!$scope.editing) {
                return;
            }

            RestangularAppService.one('timeclock/entry', entry.id).remove().then(function () {
                $modalInstance.close();
            });
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };
    });