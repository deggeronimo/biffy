'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider.state('companies.storeitems',
            {
                url: '/{companyId}/storeitems',
                preserveQueryParams: true, //Re-implement: query params will be preserved going away from this state and applied on coming back
                views: {
                    '@': {
                        templateUrl: 'src/companies/storeitems/storeitems.html',
                        controller: 'CompaniesStoreItemsController'
                    }
                }
            }
        );
    }
);
