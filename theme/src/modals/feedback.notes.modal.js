'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('FeedbackNoteModalController',
    function($scope, $modalInstance, RestangularAppService, NotifierService, feedbackId)
    {
        $scope.feedbackStatuses = [
            '', 'Waiting', 'Closed'
        ];

        $scope.feedbackStatusRange = function()
        {
            var result = [];
            for (var i = 1; i < $scope.feedbackStatuses.length; i++)
            {
                result.push($scope.feedbackStatuses[i]);
            }
            return result;
        };

        $scope.init = function(id)
        {
        };

        $scope.feedbackId = feedbackId;
        $scope.init($scope.feedbackId);

        $scope.feedbackStatusId = 1;
        $scope.notes = { 'description' : 'Enter Description Here...' };

        $scope.cancel = function()
        {
            $modalInstance.dismiss('cancel');
        };

        $scope.save = function()
        {
            var feedbackNote = {
                'feedback_id' : $scope.feedbackId,
                'feedback_status_id' : $scope.feedbackStatusId,
                'notes' : $scope.notes.description
            };

            RestangularAppService.all('feedbacknotes').post(feedbackNote).then(
                function (data)
                {
                    $modalInstance.close(feedbackNote);
                },
                function (data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };
    }
);