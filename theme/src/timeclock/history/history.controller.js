'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('TimeclockHistoryController', function ($state, $filter, $scope, RestangularAppService, $location, DateRange, StoreService) {
        $scope.history = {};

        $scope.start = DateRange.start(StoreService.config('pay-period-start'));
        angular.extend($scope.start, DateRange.searchParams());
        $scope.range = DateRange.range($scope.start);
        $scope.dateRangePickerOptions = DateRange.pickerOptions($scope.start);

        $scope.$watchGroup(['range.from', 'range.to'], function () {
            $scope.getData();
            $location.search(DateRange.paramObj($scope.range));
        });

        $scope.getData = function () {
            RestangularAppService.all('reports/timeclock').customGET('detailed/' + DateRange.parse($scope.range.from) + '/' + DateRange.parse($scope.range.to)).then(function (data) {
                $scope.history = data;
            });
        };

        $scope.detailsObj = function (userId) {
            return {id: userId, from: DateRange.parse($scope.range.from), to: DateRange.parse($scope.range.to)};
        };
    }
);