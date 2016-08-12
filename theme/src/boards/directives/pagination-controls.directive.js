'use strict';

angular.module('biffyApp')
    .directive('paginationControls', function () {
        return {
            restrict: 'E',
            templateUrl: 'src/boards/directives/pagination-controls.html',
            scope: {
                page: '=',
                perPage: '=',
                numItems: '='
            },
            controller: function ($scope, ngTableParams) {
                var params = new ngTableParams();
                $scope.pages = [];
                $scope.noWatch = true;

                $scope.buildPages = function () {
                    $scope.pages = params.generatePagesArray(parseInt($scope.page), $scope.numItems, parseInt($scope.perPage));
                };

                $scope.buildPages();

                $scope.changePage = function (page) {
                    $scope.noWatch = true;
                    $scope.page = page;
                    $scope.buildPages();
                };

                $scope.$watch('page', function () {
                    if ($scope.noWatch) { $scope.noWatch = false; return; }
                    $scope.buildPages();
                });
            }
        };
    });