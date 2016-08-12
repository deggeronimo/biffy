'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'companies.storeitems.add',
            {
                url: '/new',
                views: {
                    '@': {
                        templateUrl : 'src/companies/storeitems/edit/edit.html',
                        controller : 'CompaniesStoreItemsEditController'
                    }
                }
            }
        ).state(
            'companies.storeitems.edit',
            {
                url: '/edit/{id}',
                views: {
                    '@': {
                        templateUrl : 'src/companies/storeitems/edit/edit.html',
                        controller : 'CompaniesStoreItemsEditController'
                    }
                }
            })
        ;
    });
