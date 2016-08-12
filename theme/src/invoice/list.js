'use strict';

angular.module('biffyApp').config(
    function ($stateProvider)
    {
        $stateProvider.state(
            'invoice',
            {
                parent: 'store-ops',
                url: '/invoice',
                views: {
                    '@': {
                        templateUrl: 'src/invoice/list.html',
                        controller: 'InvoiceListController'
                    }
                },
                menu: {
                    name: 'Invoice',
                    class: 'fa fa-file-text-o',
                    tag: 'sidebar',
                    priority: 99
                }
            }
        );
    }
);
