'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('FeedbackCallLogModalController',
    function($scope, $modalInstance, RestangularAppService, NotifierService, feedbackId)
    {
        $scope.init = function(id)
        {
        };

        $scope.feedbackId = feedbackId;
        $scope.init($scope.feedbackId);

        $scope.whoCalled = 'Nobody';
        $scope.notes = { 'description' : 'Enter Notes Here...' };

        $scope.cancel = function()
        {
            $modalInstance.dismiss('cancel');
        };

        $scope.save = function()
        {
            var feedbackCallLog = {
                'feedback_id' : $scope.feedbackId,
                'who_called' : $scope.whoCalled,
                'notes' : $scope.notes.description
            };

            RestangularAppService.all('feedbackcalllogs').post(feedbackCallLog).then(
                function (data)
                {
                    $modalInstance.close(feedbackCallLog);
                },
                function (data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };
    }
);