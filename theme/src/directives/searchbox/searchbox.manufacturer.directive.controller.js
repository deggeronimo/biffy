'use strict';

angular.module('biffyApp').directive(
    'manufacturerSearchBox',
    function (RestangularAppService)
    {
        function link(scope)
        {
            scope.getManufacturerItems = function (val)
            {
                var query = {
                    all: 1,
                    filter: {
                        name: val
                    }
                };

                return RestangularAppService.all('devicemanufacturers').getList(flattenParams(query)).then(
                    function (response)
                    {
                        return response;
                    }
                );
            };
        }

        return {
            link : link,
            templateUrl : 'src/directives/searchbox/searchbox.manufacturer.directive.html'
        };
    }
);