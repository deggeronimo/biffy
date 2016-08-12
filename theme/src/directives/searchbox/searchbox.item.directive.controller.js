'use strict';

angular.module('biffyApp').directive(
    'itemSearchBox',
    function (RestangularAppService)
    {
        function link(scope)
        {
            scope.selectItem = function(item)
            {
                scope.data.item = item;
                scope.data.item_id = item.id;
            };

            scope.getItems = function (val)
            {
                return RestangularAppService.all('items?filter[search]=' + val).getList().then(
                    function (response)
                    {
                        return response;
                    }
                );
            };
        }

        return {
            link : link,
            templateUrl : 'src/directives/searchbox/searchbox.item.directive.template.html'
        };
    }
);