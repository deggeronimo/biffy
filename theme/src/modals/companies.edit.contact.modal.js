'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
	.controller('CompaniesEditContactModalController',
	function(companyId, contactId, $scope, $modalInstance, RestangularAppService, NotifierService)
	{

        $scope.init = function(companyId,customerId)
        {
            $scope.currentCustomer = RestangularAppService.one('companies', companyId).one('contacts',customerId).get().then(
                function (result)
                {
                    $scope.currentcontact = result;
                },
                function ()
                {
                    $scope.cancel();
                }
            );
        };
		$scope.cancel = function(){
			$modalInstance.dismiss('cancel');
		}

		$scope.saveContact = function(){
		    $scope.currentcontact.put().then(function(){
		      $modalInstance.close($scope.currentcontact);
		    });
		}
		$scope.init(companyId,contactId);
	}
);