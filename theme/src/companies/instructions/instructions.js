'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider.state('companies.instructions',
            {
                url: '/{companyId}/instructions',
                preserveQueryParams: true, //Re-implement: query params will be preserved going away from this state and applied on coming back
                views: {
                    '@': {
                        templateUrl: 'src/companies/instructions/instructions.html',
                        controller: 'CompaniesInstructionsController'
                    }
                }
            }
        );
    }
);
