'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'checklists.functions.edit',
            {
                url : '/edit/{id}',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/checklists/functions/edit/edit.html',
                        controller : 'ChecklistFunctionsEditController'
                    }
                }
            }
        );
    }
);
