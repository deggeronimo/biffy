'use strict';

angular.module('biffyApp').config(
    function($stateProvider)
    {
        $stateProvider.state(
            'purchasing',
            {
                parent: 'store-ops',
                url : '/purchasing',
                controller : function($state)
                {
                    $state.go('purchasing.home');
                },
                menu : {
                    name : 'Purchasing',
                    class : 'fa fa-truck',
                    tag : 'sidebar',
                    priority : 81
                }
            }
        ).state(
            'purchasing.home',
            {
                url : '/purchasing/home',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/purchasing/list.html',
                        controller : 'PurchasingHomeController'
                    }
                },
                menu :
                {
                    name : 'Purchasing List',
                    class : 'fa fa-truck',
                    tag : 'sidebar',
                    priority : 6
                }
            }
/*        ).state(
            'purchasing.config',
            {
                url : '/purchasing/config',
                preserveQueryParams : true,
                views :
                {
                    '@' :
                    {
                        templateUrl : 'src/purchasing/purchasing.config.html',
                        controller : 'PurchasingConfigController'
                    }
                },
                menu :
                {
                    name : 'Purchasing Config',
                    class : 'fa fa-truck',
                    tag : 'sidebar',
                    priority : 6
                }
            }
*/        )
        ;
    }
);
