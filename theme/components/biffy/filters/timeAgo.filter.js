'use strict';

angular.module('biffy.filters').filter('timeAgo', function() {
  return function(date) {
    return moment(date).fromNow();
  }
});
