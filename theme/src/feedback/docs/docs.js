'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'feedback.docs',
            {
                url : '/feedback/docs',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/feedback/docs/docs.html',
                        controller : 'FeedbackDocsController'
                    }
                },
                menu :
                {
                    name : 'Feedback Docs',
                    class : 'fa fa-file-archive-o',
                    tag : 'sidebar',
                    priority : 6
                }
            }
        );
    }
);
