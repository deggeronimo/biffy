'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'checklists.approvals.new',
            {
                url: '/new',
                preserveQueryParams : true,
                views: {
                    '@': {
                        templateUrl : 'src/checklists/approvals/edit/edit.html',
                        controller : 'ChecklistApprovalsEditController'
                    }
                }
            }
        ).state(
            'checklists.approvals.edit',
            {
                url : '/edit/{id}',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/checklists/approvals/edit/edit.html',
                        controller : 'ChecklistApprovalsEditController'
                    }
                }
            }
        );
    }
);
