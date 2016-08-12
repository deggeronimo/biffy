'use strict';

angular.module('biffy.filters')
    .filter('ucfirst', function () {
        return function (input, scope) {
            return input.substring(0, 1).toUpperCase() + input.substring(1);
        };
    });