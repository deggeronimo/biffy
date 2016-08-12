'use strict';

angular.module('biffy.iSpin', [])

.constant('iSpinConfig', {
  step: 1,
  min: 0,
  max: 100
})

.controller('iSpinController', function($scope, $attrs, iSpinConfig) {
  var value = $scope.modelValue || null;

  // Implemented as functions, otherwise we need to implement watches also
  var getCustomFormat = function() { return isDefined($attrs.customFormat) ? $scope.$parent.$eval($attrs.customFormat) : identity; };
  var getStep = function() { return isDefined($attrs.step) ? parseInt($scope.$parent.$eval($attrs.step)) : iSpinConfig.step; };
  var getMin = function() { return isDefined($attrs.min) ? parseInt($scope.$parent.$eval($attrs.min)) : iSpinConfig.min; };
  var getMax = function() { return isDefined($attrs.max) ? parseInt($scope.$parent.$eval($attrs.max)) : iSpinConfig.max; };

  $scope.viewValue = getCustomFormat()(value);

  this.setup = function(inputElement) {
    inputElement.bind('keydown keypress', function (event) {
      switch(event.key) {
        case "Up":
          $scope.increment();
          event.preventDefault();
          break;
        case "Down":
          $scope.decrement();
          event.preventDefault();
          break;
        default:
          return;
      }
    });
  };

  function refresh() {
    $scope.modelValue = value;
    $scope.viewValue = getCustomFormat()(value);
  }

  $scope.$watch('modelValue', function(newValue) {
    value = newValue;
    refresh();
  });

  $scope.increment = function() {
    if((value + getStep()) <= getMax()) {
      value = value + getStep();
      refresh();
    }
  };

  $scope.decrement = function() {
    if((value - getStep()) >= getMin()) {
      value = value - getStep();
      refresh();
    }
  };
})

.directive('iSpin', function() {
  return {
    restrict: 'EA',
    require: 'iSpin',
    controller: 'iSpinController',
    replace: true,
    templateUrl: 'components/biffy/iSpin/iSpin.template.html',
    scope: {
      modelValue: '=ngModel'
    },
    link: function(scope, element, attrs, controller) {
      controller.setup( element.find('input').eq(0) );
    }
  };
});
