'use strict';

angular.module('biffyApp')
    .filter('timeclockType', function () {
        return function (type) {
            switch (type) {
                case 1:
                    return 'Working';
                case 2:
                    return 'Break';
                default:
                    return '';
            }
        };
    });