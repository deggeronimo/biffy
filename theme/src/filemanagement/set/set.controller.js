'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
.controller('FilemanagementSetController', function($rootScope, $state, $stateParams, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService, $http) {

  $scope.id = $stateParams.id || null;

  $q.all([
        RestangularAppService.one('filesets', $scope.id).get()
      ]).then(function(result) {
    $scope.set = result[0];
    $scope.tableParams = new ngTableParams(
        angular.extend(
            {
              page: 1,
              count: 10,
              sorting: {
                path: 'asc'
              }
            },
            $location.search()
        ), {
          total: 0,
          getData: function($defer, params) {
            $location.search(params.url());
            RestangularAppService.one('filesets', $scope.id).all('files').getList(params.url()).then(function(result) {
              $scope.tableParams.settings({total: result.paginator.total});
              $defer.resolve(result);
            }, function() {
              NotifierService.error('Files could not be loaded');
            });
          }
        })
    ;
  });

  $scope.uploadFiles = function(files) {
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var url = RestangularAppService.one('filesets', $scope.id).all('files').getRestangularUrl();
      var fd = new FormData();

      fd.append('upload', file);
      // We can alternately send multiple files in a single multi-part upload as well
      // for (var i = 0; i < $files.length; i++) { fd.append('upload[]', $files[i]); }
      // However in our case each file is added as a new File entity so sending them one by one is more RESTful approach
      // Another reason of sending these one by one script execution timeout limits in php

      fd.append('file_set_id', $scope.id); //multi-part upload is required to set this id

      $http.post(url, fd, {
        transformRequest: angular.identity,
        headers: {'Content-Type': undefined}
      }).success(function(data) {
        NotifierService.success( data.data[0].path + ' uploaded successfully');
      }).error(function() {
        NotifierService.error( (file.name || 'File') + ' could not be uploaded');
      });
    }
    $rootScope.reload();
  };

  $scope.resetInputFile = function() {
    var elems = document.getElementsByTagName('input');
    for (var i = 0; i < elems.length; i++) {
      if (elems[i].type == 'file') {
        elems[i].value = null;
      }
    }
  };

  $scope.destroy = function(item) {
    item.remove().then(function() {
      $rootScope.reload();
    }, function(data) {
      NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
    });
  };

});
