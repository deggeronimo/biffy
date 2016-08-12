'use strict';

/* jshint undef: false */

angular.module('biffy.export')
.provider('ExportService', function() {
  var baseUrl = '/export';
  return {
    setBaseUrl: function(prefix) {
      baseUrl = prefix;
    },
    $get: function($window) {
      return {
        getUrl: function(resource, params) {
          return baseUrl + '/' + resource + '?' + toKeyValue(params);
        },
        go: function(resource, params) {
          $window.location.href = this.getUrl(resource, params);
        }
      };
    }
  };
});