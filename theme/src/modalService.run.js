'use strict';

angular.module('biffyApp')
.run(function($rootScope, $state, ModalService) {
	$rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
	  if(isDefined(toState.modal) && toState.modal) {
		event.preventDefault();
		var modal = {
		  size: 'md', // Size of modal window
		  reload: true, // to reload after close (success), We can ignore fail condition for reload | broadcast
		  broadcast: null // broadcast message on close (success)
		};
		modal = extend(modal, toState.modal || {});
		ModalService.openEdit(toState.templateUrl, toState.controller, toParams, toState.modal.size).result.then(function() {
		  if( modal.broadcast ) {
			$rootScope.$broadcast(modal.broadcast);
		  }
		  if( modal.reload ) {
			  $rootScope.reload();
		  }
		});
	  }
	});
});
