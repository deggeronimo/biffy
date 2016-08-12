'use strict';

angular.module('biffyApp')
    .directive('postEdit', function ($state) {
        return {
            restrict: 'E',
            templateUrl: 'src/boards/thread/directives/post-edit.html',
            scope: {
                user: '=',
                routeVal: '=',
                route: '=',
                closed: '='
            },
            link: function (scope) {
                scope.url = $state.href(scope.route, scope.routeVal);
            },
            controller: function ($scope, $state, UserService) {
                $scope.canEdit = false;
                var user = UserService.getUser();

                if (user.id === $scope.user && !$scope.closed) {
                    $scope.canEdit = true;
                } else {
                    if (UserService.hasPermission('message-boards.edit-post')) {
                        $scope.canEdit = true;
                    }
                }
            }
        }
    });