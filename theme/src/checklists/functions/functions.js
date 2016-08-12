'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'checklists.functions',
            {
                url : '/functions',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/checklists/functions/functions.html',
                        controller : 'ChecklistFunctionsController'
                    }
                },
                menu :
                {
                    name : 'Functions',
                    class : 'fa fa-check',
                    tag : 'sidebar',
                    priority : 6
                }
            }
        );
    }
);
