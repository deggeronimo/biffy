'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'purchasing.edit',
            {
                url : '/edit/{id}',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/purchasing/edit/edit.html',
                        controller : 'PurchasingEditController'
                    }
                },
                menu :
                {
                    name : 'Purchasing Edit',
                    class : 'fa fa-truck',
                    tag : 'sidebar',
                    priority : 6
                }
            }
        );
    }
);
