'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
	.controller('CompaniesNewContactModalController',
	function($scope, $modalInstance, RestangularAppService, NotifierService,companyId)
	{
		$scope.companyId = companyId;
		$scope.cancel = function(){
			$modalInstance.dismiss('cancel');
		}

		$scope.save = function(){
			console.log($scope.contact)
			$scope.contact.company_id = $scope.companyId;
			RestangularAppService.all('companies/'+$scope.companyId + '/contacts').post($scope.contact).then(function(result) {
				$modalInstance.close($scope.contact);
			}, function(data) {
				NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
			});
		}
	}
);