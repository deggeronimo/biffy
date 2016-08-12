'use strict';

angular.module('biffyApp')
    .config(function ($stateProvider) {
        $stateProvider.state('boards.post', {
            url: '/post'
        });
    });