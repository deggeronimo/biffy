'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('FeedbackExistingDocumentModalController',
    function($scope, $modalInstance, RestangularAppService, NotifierService, feedbackId)
    {
        $scope.documentList = RestangularAppService.all('feedbackdocs/?unassigned=1').getList().$object;

        $scope.findById = function(arr, id)
        {
            for (var i=0; i<arr.length; i++)
            {
                if (arr[i].id == id)
                {
                    return arr[i];
                }
            }

            return null;
        };

        $scope.init = function(id)
        {
        };

        $scope.feedbackId = feedbackId;
        $scope.init($scope.feedbackId);

        $scope.currentImage = null;

        $scope.add = function(id)
        {
            var document = $scope.findById($scope.documentList, id);

            RestangularAppService.one('feedbackdocs', id).put({ 'feedback_id' : feedbackId }).then(
                function(data)
                {
                    var i = $scope.documentList.indexOf(document);
                    $scope.documentList.splice(i, 1);

                    $scope.currentImage = null;
                },
                function(data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.del = function(id)
        {
            var document = $scope.findById($scope.documentList, id);

            RestangularAppService.one('feedbackdocs', id).remove().then(
                function()
                {
                    var i = $scope.documentList.indexOf(document);
                    $scope.documentList.splice(i, 1);

                    $scope.currentImage = null;
                },
                function(data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.preview = function(id)
        {
            $scope.currentImage = $scope.findById($scope.documentList, id);
        };

        $scope.finished = function()
        {
            $modalInstance.close({});
        };
    }
);