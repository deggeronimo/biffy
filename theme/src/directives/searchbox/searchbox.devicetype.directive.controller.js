'use strict';

angular.module('biffyApp').directive(
    'deviceTypeSearchBox',
    function (RestangularAppService)
    {
        function link(scope)
        {
            scope.getDeviceTypeItems = function (val)
            {
                var query = {
                    all: 1,
                    filter: {
                        name: val
                    }
                };

                return RestangularAppService.all('devicetypes').getList(flattenParams(query)).then(
                    function (response)
                    {
                        return response;
                    }
                );
            };
        }

        return {
            restrict: 'E',
            link : link,
            templateUrl : 'src/directives/searchbox/searchbox.devicetype.directive.template.html'
        };
    }
);