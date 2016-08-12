'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'website.groups.edit',
            {
                url: '/edit/{id}',
                views: {
                    '@': {
                        templateUrl: 'src/website/groups/edit/edit.html',
                        controller: 'WebsiteGroupsEditController'
                    }
                }
            })
        ;
    }
);
