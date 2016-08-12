'use strict';

// Uses moment library for the purpose of formatting
angular.module('biffy.iDtp')

.constant('iDtpConfig', {
  format: "MMM D, YYYY hh:mm a"
})

.directive('iDtp', function(iDtpConfig, $document, $position, $moment) {
  return {
	restrict: 'EA',
	templateUrl: 'components/biffy/iDtp/iDtp.template.html',
	replace: true,
	scope: {
	  modelValue: '=ngModel',
	  icon: '@',
	  isOpen: '=?'
	},
	link: function(scope, element, attrs) {

	  var inputEl = element.find('input').eq(0);

	  var value,
		  format = iDtpConfig.format;

	  function refresh() {
		scope.viewValue = value ? $moment(value).format(format) : "";
	  }

	  scope.$watch('modelValue', function(newValue) {
		value = newValue ? (isDate(newValue) ? newValue : (isString(newValue) ? $moment(newValue, format).toDate() : null ) ) : null;
		refresh();
	  });

	  // We are implementing observe on format as we need to call the refresh method
	  // Implementation of icon is comparable but different
	  attrs.$observe('format', function(newValue) {
		format = scope.$parent.$eval(newValue);
		refresh();
	  });

	  var keydown = function (evt) {
		if (evt.which === 27) { // Esc key
		  evt.preventDefault();
		  evt.stopPropagation();
		  scope.isOpen = false;
		  inputEl.focus();
		} else if (evt.which === 40 && !scope.isOpen) { // Down arrow
		  scope.isOpen = true;
		}
	  };

	  element.bind('keydown keypress', keydown);

	  var documentClickBind = function (event) {
		if (scope.isOpen && event.target !== element[0]) {
		  scope.$apply(function () {
			scope.isOpen = false;
		  });
		}
	  };

	  element.bind('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
	  });

	  scope.$watch('isOpen', function (value) {
		if (value) {
		  scope.position = $position.position(element);
		  scope.position.top = scope.position.top + element.prop('offsetHeight');
		  $document.bind('click', documentClickBind);
		} else {
		  $document.unbind('click', documentClickBind);
		}
	  });

	  scope.clickToggle = function(evt) {
		evt.preventDefault();
		evt.stopPropagation();
		scope.isOpen = ! scope.isOpen;
		inputEl.focus();
	  };

	  scope.select = function(dt) {
		if(dt === 'now') {
		  scope.modelValue = $moment($moment().format(format), format).toDate();
		} else if(isDate(dt)) {
		  scope.modelValue = dt;
		} else if(isString(dt)) {
		  scope.modelValue = $moment(dt, format).toDate()
		} else {
		  scope.modelValue = null;
		}
		scope.isOpen = false;
		inputEl.focus();
	  };

	  scope.close = function(evt) {
		scope.isOpen = false;
		inputEl.focus();
	  };

	  scope.$on('$destroy', function () {
		element.unbind('keydown keypress', keydown);
		$document.unbind('click', documentClickBind);
	  });

	}
  };
});
