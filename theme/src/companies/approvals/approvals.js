'use strict';

angular.module('biffyApp')
    .config(function($stateProvider) {
        $stateProvider.state('companies.approvals',
            {
                url: '/{companyId}/approvals',
                preserveQueryParams: true, //Re-implement: query params will be preserved going away from this state and applied on coming back
                views: {
                    '@': {
                        templateUrl: 'src/companies/approvals/approvals.html',
                        controller: 'CompaniesApprovalsController'
                    }
                }
            }
        );
    }
);
