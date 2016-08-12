'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'checklists',
            {
                parent: 'data-management',
                url : '/checklists',
                controller : function($state)
                {
                },
                menu : {
                    name : 'Checklists',
                    class : 'fa fa-check',
                    tag : 'sidebar',
                    priority : 80
                }
            }
        );
    }
);
