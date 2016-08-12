'use strict';

angular.module('biffy.breadcrumb')
    .service('BreadcrumbService', function ($rootScope, $state) {
        var self = this;
        this.breadcrumbs = [];

        this.clear = function () {
            self.breadcrumbs = [];
        };

        this.add = function (data) {
            if (data.state) {
                data.url = $state.href(data.state, data.stateParams);
            }
            self.breadcrumbs.push(data);
        };

        $rootScope.$on('$stateChangeStart', function () {
            self.clear();
        });
    });