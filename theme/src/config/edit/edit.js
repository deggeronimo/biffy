'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('config.edit', {
            url: '/config/edit/{id}',
            views: {
                '@': {
                    templateUrl: 'src/config/edit/edit.html',
                    controller: 'ConfigEditController'
                }
            }
        });
    });