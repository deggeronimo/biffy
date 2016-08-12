'use strict';

angular.module('biffy.filters')
    .filter('birthday', function () {
        return function (val) {
            return moment(val, 'YYYY-MM-DD').format('MMMM D, YYYY');
        };
    });