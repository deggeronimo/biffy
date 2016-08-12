'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'MarketingController',
    function ($modal, $scope, RestangularAppService, ngTableParams, $location, NotifierService)
    {
        $scope.pageState = 'init';

        $scope.init = function()
        {
            RestangularAppService.one('marketingrunstate', 1).get().then(
                function(data)
                {
                    if (data.state == 'none')
                    {
                        $scope.pageState = 'home';
                    }
                    else if (data.state == 'running')
                    {
                        $scope.pageState = 'running';
                        $scope.marketingRunId = data.id;

                        $scope.reloadVisitsList();
                    }
                },
                function()
                {
                }
            );
        };

        $scope.beginMarketingRun = function()
        {
            RestangularAppService.all('marketingruns').post().then(
                function(data)
                {
                    $scope.pageState = 'running';
                    $scope.marketingRunId = data.id;
                }
            );
        };

        $scope.GetLocation = function(location)
        {
            RestangularAppService.all('marketinglocations/' + location.coords.latitude + '/' + location.coords.longitude).getList().then(
                function(result)
                {
                    $scope.locationList = result;

                    console.log($scope.locationList);
                },
                function()
                {

                }
            );
        };

        $scope.logVisit = function()
        {
            $scope.pageState = 'logging';

            navigator.geolocation.getCurrentPosition($scope.GetLocation);
        };

        $scope.selectLocation = function(location)
        {
            RestangularAppService.one('marketingruns', $scope.marketingRunId).put({
                marketing_location_id: location.id,
                marketing_run_type_id: 1,
                comments: 'Comments'
            }).then(
                function()
                {
                    $scope.pageState = 'running';
                    $scope.reloadVisitsList();
                },
                function()
                {
                }
            );
        };

        $scope.stopMarketingRun = function()
        {
            RestangularAppService.one('marketingruns', $scope.marketingRunId).put({
                stopped: 1
            }).then(
                function()
                {
                    $scope.pageState = 'home';
                },
                function()
                {
                }
            );
        };

        $scope.runsTableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                        created_at: 'desc'
                    }
                },
                $location.search()
            ), {
                total: 0,
                getData: function($defer, params) {
                    if ($scope.pageState != 'home')
                    {
                        return;
                    }

                    $location.search(params.url());

                    RestangularAppService.all('marketingruns').getList(params.url()).then(
                        function(result)
                        {
                            $scope.runsTableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        },
                        function()
                        {
                            NotifierService.error('Marketing Runs could not be loaded');
                        }
                    );
                }
            }
        );

        $scope.reloadVisitsList = function()
        {
            RestangularAppService.one('marketingruns', $scope.marketingRunId).get().then(
                function(result)
                {
                    $scope.visitsList = result.marketing_locations;
                },
                function()
                {
                    NotifierService.error('Marketing Runs could not be loaded');
                }
            );
        };

        $scope.init();
    }
);
