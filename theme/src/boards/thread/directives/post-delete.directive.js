'use strict';

angular.module('biffyApp')
    .directive('postDelete', function () {
        return {
            restrict: 'E',
            templateUrl: 'src/boards/thread/directives/post-delete.html',
            scope: {
                action: '&',
                user: '=',
                closed: '='
            },
            controller: function ($scope, UserService) {
                $scope.canDelete = false;
                var user = UserService.getUser();

                if (user.id === $scope.user && !$scope.closed) {
                    $scope.canDelete = true;
                } else {
                    if (UserService.hasPermission('message-boards.delete-post')) {
                        $scope.canDelete = true;
                    }
                }
            }
        };
    });