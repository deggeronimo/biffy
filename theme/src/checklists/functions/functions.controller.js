'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'ChecklistFunctionsController',
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

                if (dataRow.name.toLowerCase().indexOf($scope.search.toLowerCase()) != -1)
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
                    $location.search(params.url());
                    RestangularAppService.all('checklistfunctions').getList(params.url()).then(
                        function(result)
                        {
                            $defer.resolve(result);
                        },
                        function()
                        {
                            NotifierService.error('Checklist Functions could not be loaded');
                        }
                    );
                }
            }
        );
    }
);
