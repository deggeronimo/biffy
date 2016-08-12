'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('EmployeesListController', function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService) {
        $scope.tableParams = new ngTableParams(
            {
                sorting: {
                    family_name: 'asc' // todo get sorting to work
                }
            }, {
                counts: [],
                getData: function($defer, params) {
                    RestangularAppService.all('employees').getList().then(function(result) {
                        $defer.resolve(result);
                    }, function() {
                        NotifierService.error('Employees could not be loaded');
                    });
                }
            }
        );
    });
