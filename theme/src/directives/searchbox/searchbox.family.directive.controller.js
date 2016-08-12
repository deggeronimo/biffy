'use strict';

angular.module('biffyApp').directive(
    'familySearchBox',
    function (RestangularAppService)
    {
        function link(scope)
        {
            scope.getFamilyItems = function (val)
            {
                var query = {
                    all: 1,
                    filter: {
                        name: val
                    }
                };

                return RestangularAppService.all('devicefamilies').getList(flattenParams(query)).then(
                    function (response)
                    {
                        return response;
                    }
                );
            };
        }

        return {
            link : link,
            templateUrl : 'src/directives/searchbox/searchbox.family.directive.html'
        };
    }
);