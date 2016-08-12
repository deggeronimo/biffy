'use strict';

angular.module('biffy.dynamicInput')
    .directive('dynamicInput', function ($http, UserService) {
        return {
            restrict: 'E',
            scope: {
                base: '=',
                value: '='
            },
            link: function (scope, element, attr) {
                switch (scope.base.type) {
                    case 'select':
                        scope.options = JSON.parse(scope.base.extra);
                        break;
                    case 'timezones':
                    case 'days-of-week':
                        // todo get data without using http request
                        $http.get('/assets/json/' + scope.base.type + '.json').then(function (data) {
                            scope.options = data.data;
                        });
                        scope.base.type = 'select';
                        break;
                    case 'user-store-list':
                        scope.options = UserService.getStores();
                        break;
                    default:
                        break;
                }
            },
            templateUrl: 'components/biffy/dynamicInput/base.html',
            controller: function ($scope) {
                $scope.templateUrl = function () {
                    return 'components/biffy/dynamicInput/' + $scope.base.type + '.html';
                };
            }
        };
    });