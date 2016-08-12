'use strict';

/* jshint undef: false */

angular.module('biffy.modal').factory('ModalService', function($modal) {

	return {
	  openEdit: function(templateUrl, controller, modalParams, size) {
		return $modal.open({
		  templateUrl: templateUrl,
		  controller: controller,
		  size: size,
		  resolve: {
			modalParams: function() { return modalParams; }
		  }
		});
	  }
	}
});
