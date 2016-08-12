'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'website',
            {
                parent: 'data-management',
                url: '/website',
                menu: {
                    name: 'Website',
                    class: 'fa fa-server',
                    tag: 'sidebar',
                    priority: 90
                }
            }
        );
    }
);
