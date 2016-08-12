'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider
            .state('menu-section', {
                parent: 'authorized',
                views: {
                    '@': {
                        templateUrl: 'src/menu-sections/menu-section.html',
                        controller: function ($scope, $state) {
                            $scope.currentState = function () {
                                return $state.current.name;
                            };
                        }
                    }
                }
            });
    });