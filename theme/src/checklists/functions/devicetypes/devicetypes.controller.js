'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'ChecklistFunctionsDeviceTypesController',
    function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService)
    {
        $scope.search = '';

        $scope.filter = function(data)
        {
            if ($scope.search == '')
            {
                return data;
            }

            var results = [];

            for (var i=0; i<data.length; i++)
            {
                var dataRow = data[i];

                if (dataRow.device_type.name.toLowerCase().indexOf($scope.search.toLowerCase()) != -1)
                {
                    results.push(dataRow);
                }
                else if (dataRow.checklist_function.name.toLowerCase().indexOf($scope.search.toLowerCase()) != -1)
                {
                    results.push(dataRow);
                }
                else if (dataRow.item.name.toLowerCase().indexOf($scope.search.toLowerCase()) != -1)
                {
                    results.push(dataRow);
                }
            }

            return results;
        };

        $scope.tableParams = new ngTableParams(
            angular.extend(
                {
                    page : 1,
                    count : 10,
                    sorting:
                    {
                        name : 'asc'
                    }
                },
                $location.search()
            ), {
                total: 0,
                getData: function($defer, params)
                {
                    RestangularAppService.all('devicechecklists?full=1').getList(params.url()).then(
                        function(result)
                        {
//                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result.plain());
                        },
                        function()
                        {
                            NotifierService.error('Checklist Functions Device Types could not be loaded');
                        }
                    );
                }
            }
        );
    }
);
