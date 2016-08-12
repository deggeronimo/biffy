'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('TimeclockListController', function ($state, $scope, $filter, $q, $location, RestangularAppService, ngTableParams, NotifierService) {
        $scope.clockedIn = false;
        $scope.onBreak = false;

        $scope.tableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10
                },
                $location.search()
            ), {
                total: 0,
                getData: function ($defer, params) {
                    $location.search(params.url());

                    RestangularAppService.all('timeclock').customGET('entries', params.url()).then(
                        function (result) {
                            $scope.tableParams.settings({total: result.paginator.total});

                            $scope.clockedIn = result.clockedIn;
                            $scope.onBreak = result.onBreak;

                            $defer.resolve(result.entries);
                        },
                        function () {
                            NotifierService.error('Timeclock could not be loaded');
                        }
                    );
                }
            }
        );

        $scope.clockIn = function () {
            RestangularAppService.all('timeclock/clock-in').customPOST().then(
                function () {
                    $scope.clockedIn = true;
                    $scope.tableParams.reload();
                }
            );
        };

        $scope.clockOut = function () {
            RestangularAppService.all('timeclock/clock-out').customPOST().then(
                function () {
                    $scope.clockedIn = false;
                    $scope.tableParams.reload();
                }
            );
        };

        $scope.breakStart = function () {
            RestangularAppService.all('timeclock/break-start').customPOST().then(
                function () {
                    $scope.clockedIn = false;
                    $scope.onBreak = true;
                    $scope.tableParams.reload();
                }
            );
        };

        $scope.breakEnd = function () {
            RestangularAppService.all('timeclock/break-end').customPOST().then(
                function () {
                    $scope.clockedIn = true;
                    $scope.onBreak = false;
                    $scope.tableParams.reload();
                }
            );
        };
    }
);