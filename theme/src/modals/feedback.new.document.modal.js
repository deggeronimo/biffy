'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('FeedbackNewDocumentModalController',
    function($scope, $modalInstance, RestangularAppService, NotifierService, feedbackId)
    {
        $scope.init = function(id)
        {
        };

        $scope.feedbackId = feedbackId;
        $scope.init($scope.feedbackId);

        $scope.documentType = 1;
        $scope.notes = { 'description' : 'Enter Description Here...' };

        $scope.$watch(
            'file',
            function()
            {
                console.log($scope.file);
            }
        );


        $scope.cancel = function()
        {
            $modalInstance.dismiss('cancel');
        };

        $scope.save = function()
        {
            console.log(document.getElementById('file').files[0]);

            var data = new FormData();

            data.append('feedback_id', $scope.feedbackId);
            data.append('feedback_doctype_id', $scope.documentType);
            data.append('description', $scope.notes.description);
            data.append('file', document.getElementById('file').files[0]);

            RestangularAppService.all('feedbackdocs').withHttpConfig( { transformRequest: angular.identity } )
                .post(data, {}, { 'Content-Type': undefined } ).then(

                function (data)
                {
                    $modalInstance.close(data);
                },
                function (data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };
    }
);