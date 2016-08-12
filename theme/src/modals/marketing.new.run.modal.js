'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
	.controller('MarketingNewRunModalController',
	function($scope, $modalInstance, RestangularAppService, NotifierService)
	{
		$scope.cancel = function(){
			$modalInstance.dismiss('cancel');
		}

		$scope.save = function(e){
			var location = {
				'name':e.name,
				'marketing_location_type_id': 1,
				'latitude': e.latitude,
				'longitude': e.longitude,
				'address': e.address,
				'phone': e.phone
			}
			console.log(location)
			RestangularAppService.all('marketinglocations').post(location).then(function(result) {
				$modalInstance.close(location);
			}, function(data) {
				NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
			});
		}
	}
);