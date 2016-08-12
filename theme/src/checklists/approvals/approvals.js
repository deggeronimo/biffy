'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'checklists.approvals',
            {
                url : '/approvals',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/checklists/approvals/approvals.html',
                        controller : 'ChecklistApprovalsController'
                    }
                },
                menu :
                {
                    name : 'Device Approvals',
                    class : 'fa fa-check',
                    tag : 'sidebar',
                    priority : 6
                }
            }
        );
    }
);
