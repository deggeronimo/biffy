'use strict';

angular.module('biffyApp')
    .controller('TimeclockDetailsController', function ($scope, RestangularAppService, $stateParams, DateRange, $location, ngTableParams, NotifierService, $modal) {
        $scope.userId = $stateParams.id;
        $scope.start = DateRange.startFromUrl($stateParams);
        $scope.range = DateRange.range($scope.start);
        $scope.dateRangePickerOptions = DateRange.pickerOptions($scope.start);
        $scope.first = true;

        $scope.tableParams = new ngTableParams(
            {
                page: 1,
                count: 10
            }, {
                total: 0,
                getData: function ($defer, params) {
                    var paramsObj = angular.copy(params.url());
                    angular.extend(paramsObj, $location.search());
                    var paramsObjCopy = angular.copy(paramsObj); // this stops ngTable from reloading 800 times for no reason
                    angular.extend(paramsObjCopy, DateRange.paramObj($scope.range));
                    $location.search(paramsObjCopy);

                    RestangularAppService.all('timeclock').customGET('details/' + $scope.userId, paramsObjCopy).then(
                        function (result) {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result.entries);
                        },
                        function () {
                            NotifierService.error('Timeclock could not be loaded');
                        });
                }
            }
        );

        $scope.$watchGroup(['range.from', 'range.to'], function () {
            if ($scope.first) {
                $scope.first = false;
                return;
            }

            if (typeof $scope.range.from !== 'object') {
                return;
            }

            $scope.range = DateRange.range($scope.range);
            $scope.tableParams.reload();
        });

        $scope.addModal = function (clockType) {
            var modalInstance = $modal.open({
                templateUrl: 'src/timeclock/details/edit.modal.html',
                controller: 'TimeclockDetailsEditModalController',
                size: 'lg',
                resolve: {
                    entry: function () {
                        return {
                            user_id: $scope.userId,
                            time_in: DateRange.nowTimestamp(),
                            time_out: DateRange.nowTimestamp(),
                            clock_type: clockType
                        };
                    },
                    editing: function () {
                        return false;
                    }
                }
            });

            modalInstance.result.then(function () {
                $scope.tableParams.reload();
            });
        };

        $scope.editModal = function (e) {
            var modalInstance = $modal.open({
                templateUrl: 'src/timeclock/details/edit.modal.html',
                controller: 'TimeclockDetailsEditModalController',
                size: 'lg',
                resolve: {
                    entry: function () {
                        return e;
                    },
                    editing: function () {
                        return true;
                    }
                }
            });

            modalInstance.result.then(function () {
                $scope.tableParams.reload();
            });
        };
    });