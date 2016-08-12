'use strict';

angular.module('biffy.breadcrumb')
    .directive('breadcrumbs', function (BreadcrumbService) {
        return {
            restrict: 'E',
            templateUrl: 'components/biffy/breadcrumb/breadcrumb.directive.html',
            link: function (scope, element, attrs) {
                scope.breadcrumbs = BreadcrumbService.breadcrumbs;
            }
        };
    });