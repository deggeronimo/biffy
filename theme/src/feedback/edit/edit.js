'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'feedback.edit',
            {
                url : '/feedback/edit/{feedbackId}',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/feedback/edit/edit.html',
                        controller : 'FeedbackEditController'
                    }
                },
                menu :
                {
                    name : 'Edit Feedback',
                    class : 'fa fa-file-o',
                    tag : 'sidebar',
                    priority : 6
                }
            }
        );
    }
);
