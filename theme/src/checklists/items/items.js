'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'checklists.items',
            {
                url : '/items',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/checklists/items/items.html',
                        controller : 'ChecklistItemsController'
                    }
                },
                menu :
                {
                    name : 'With Device',
                    class : 'fa fa-check',
                    tag : 'sidebar',
                    priority : 6
                }
            }
        );
    }
);
