'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'inventory',
            {
                parent: 'pos',
                url : '/inventory',
                views :
                {
                    '@' :
                    {
                        templateUrl: 'src/inventory/list.html',
                        controller: 'InventoryController'
                    }
                },
                menu:
                {
                    name: 'Inventory',
                    class: 'fa fa-cubes',
                    tag: 'sidebar',
                    priority: 50
                }
            }
        );
    }
);
