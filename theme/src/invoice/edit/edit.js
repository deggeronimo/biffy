'use strict';

angular.module('biffyApp').config(
    function ($stateProvider)
    {
        $stateProvider.state(
            'invoice.edit',
            {
                url: '/edit/{id}',
                views: {
                    '@': {
                        templateUrl: 'src/invoice/edit/edit.html',
                        controller: 'InvoiceEditController'
                    }
                }
            }
        );
    }
);
