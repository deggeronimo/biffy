angular.module('theme.controllers')
.controller('ColorPickerController', ['$scope', '$global', function ($scope, $global) {
  $scope.headerStylesheet = 'default.css';
  $scope.sidebarStylesheet = 'default.css';
  $scope.headerBarHidden = $global.get('headerBarHidden');
  $scope.headerFixed = $global.get('fixedHeader');

  $scope.setHeaderStyle = function (filename, $event) {
    $event.preventDefault();
    $event.stopPropagation();

    $scope.headerStylesheet = filename;
  };

  $scope.setSidebarStyle = function (filename, $event) {
    $event.preventDefault();
    $event.stopPropagation();

    $scope.sidebarStylesheet = filename;
  };

  $scope.$watch('headerFixed', function (newVal) {
    if (newVal === undefined) return;
    $global.set('fixedHeader', newVal);
  });
}]);
