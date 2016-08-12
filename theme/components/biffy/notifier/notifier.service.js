'use strict';

/* jshint undef: false */

angular.module('biffy.notifier').value('vToastr', toastr);

angular.module('biffy.notifier').factory('NotifierService', ['vToastr', function(vToastr) {

	function init(type) {
		return function(msg, options) {
			type = type || 'success';
			vToastr.options = angular.extend({ positionClass: 'toast-bottom-right'}, options);
			vToastr[type](msg);
		};
	}

	return {
		info: init('info'),
		success: init('success'),
		warning: init('warning'),
		error: init('error')
	};
}]);
