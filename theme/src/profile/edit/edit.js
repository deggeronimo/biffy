'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('profile.edit', {
            url: '/edit',
            views: {
                '@': {
                    templateUrl: 'src/profile/edit/edit.html',
                    controller: 'ProfileEditController'
                }
            }
        });
    });