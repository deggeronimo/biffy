'use strict';

angular.module('biffy.filters').filter('noComment', function() {
  return function(value) {
    return value || 'no comment';
  }
});
