'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'feedback',
            {
                parent: 'support',
                url : '/feedback',
                controller : function($state)
                {
                    $state.go('feedback.list');
                },
                menu : {
                    name : 'Feedback',
                    class : 'fa fa-male',
                    tag : 'sidebar',
                    priority : 80
                }
            }
        )
        .state(
            'feedback.list',
            {
                url : '/feedback/list',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/feedback/list.html',
                        controller : 'FeedbackListController'
                    }
                },
                menu :
                {
                    name : 'Feedback List',
                    class : 'fa fa-navicon',
                    tag : 'sidebar',
                    priority : 6
                }
            }
        );
    }
);
