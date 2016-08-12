'use strict';

angular.module('biffyApp')
    .config(function(RestangularProvider) {
        RestangularProvider.setDefaultHeaders({/*Version: 'v1',*/ 'X-Requested-With': 'XMLHttpRequest'});
        RestangularProvider.addResponseInterceptor(function(data) {
            var extractedData;
            extractedData = angular.isDefined(data.data) ? data.data : [];
            extractedData.paginator = angular.isDefined(data.paginator) ? data.paginator : [];
            extractedData.messages = angular.isDefined(data.messages) ? data.messages : [];
            return extractedData;
        });
    })
    .factory('RestangularAuthService', function(Restangular) {
        return Restangular.withConfig(function(RestangularConfigurer) {
            RestangularConfigurer.setBaseUrl('/api/auth');
        });
    })
    .factory('RestangularAppService', function(Restangular, UserService, $q, NotifierService) {
        return Restangular.withConfig(function(RestangularConfigurer) {
            RestangularConfigurer.setBaseUrl('/api');
            // todo implement when available: https://github.com/mgonto/restangular/pull/893
            //RestangularConfigurer.addFullRequestInterceptor(function (element, operation, what, url, headers, queryParams) {
            //    var headersFn = function (_headers) {
            //        var deferred = $q.defer();
            //        UserService.user().then(function (user) {
            //            _headers['X-Store-Id'] = user.store_id;
            //            deferred.resolve(_headers);
            //        });
            //        return deferred.promise;
            //    };
            //    return {
            //        headers: headersFn
            //    };
            //});
            RestangularConfigurer.setErrorInterceptor(function (response, deferred, responseHandler) {
                if (response.status === 409) {
                    NotifierService.error(response.data.messages.error);
                    return false;
                }
            });
        });
    });
