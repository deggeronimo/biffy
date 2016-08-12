'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'website.groups',
            {
                url: '/groups',
                views: {
                    '@': {
                        templateUrl: 'src/website/groups/groups.html',
                        controller: 'WebsiteGroupsController'
                    }
                },
                menu: {
                    name: 'Filter Groups',
                    class: 'fa fa-server',
                    tag: 'sidebar',
                    priority: 50
                }
            }
        );
    }
);
