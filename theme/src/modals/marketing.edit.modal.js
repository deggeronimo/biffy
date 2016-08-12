'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
	.controller('MarketingEditModalController',
	function(marketId, $scope, $modalInstance, RestangularAppService, NotifierService)
	{

        $scope.init = function(marketId)
        {
            $scope.currentCustomer = RestangularAppService.one('marketinglocations', marketId).get().then(
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
		$scope.init(marketId);
	}
);