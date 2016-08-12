'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
.controller('FilemanagementListController', function($rootScope, $state, $scope, $q, $location, $filter, RestangularAppService, NotifierService) {

  $q.all([
    RestangularAppService.all('filecategories').customGETLIST('select'),
  ]).then(function(result) {
    $scope.categories = result[0];
    RestangularAppService.all('filecategories').getList({'count': 100}).then(function(result) {
      $scope.categories = result;
    }, function() {
      NotifierService.error('File category sets could not be loaded');
    });
  });

  $scope.options = {};

  $scope.toggle = function(scope) {
    scope.toggle();
  }

  $scope.selectedSetScope = null;

  $scope.toggleSet = function(scope, set) {
    if($scope.selectedSetScope) $scope.selectedSetScope.toggle();
    $scope.selectedSetScope = scope;
    $scope.selectedSetScope.toggle();
    $state.transitionTo('filemanagement.set', {id: set.id});
  }

  $scope.deleteCategory = function(category) {
    category.remove().then(function() {
      $state.transitionTo('filemanagement', {}, {
        reload: true,
        inherit: false,
        notify: true
      });
    }, function(data) {
      NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
    });
  }

  $scope.deleteSet = function(set) {
    RestangularAppService.one('filesets', set.id).remove().then(function() {
      $state.transitionTo('filemanagement', {}, {
        reload: true,
        inherit: false,
        notify: true
      });
    }, function(data) {
      NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
    });
  }

  $scope.newCategory = { name: '' };

  $scope.addCategory = function() {
    RestangularAppService.all('filecategories').post($scope.newCategory).then(function() {
      $state.transitionTo('filemanagement', {}, {
        reload: true,
        inherit: false,
        notify: true
      });
    }, function(data) {
      NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
    });
  }

  $scope.addSet = function(category) {
    RestangularAppService.all('filesets').post({file_category_id: category.id, name: category.newSetName}).then(function() {
      $state.transitionTo('filemanagement', {}, {
        reload: true,
        inherit: false,
        notify: true
      });
    }, function(data) {
      NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
    });
  }

});
