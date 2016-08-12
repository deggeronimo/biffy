'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('profile', {
            parent: 'authorized',
            url: '/profile/{userId}',
            views: {
                '@': {
                    templateUrl: 'src/profile/view.html',
                    controller: 'ProfileViewController'
                }
            }
        });
    });