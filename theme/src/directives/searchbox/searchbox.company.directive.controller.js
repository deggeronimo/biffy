'use strict';

angular.module('biffyApp').directive(
    'companySearchBox',
    function (RestangularAppService)
    {
        function link(scope)
        {
            scope.getCompanyItems = function (val)
            {
                return RestangularAppService.all('companies?all=1&filter[search]=' + val).getList().then(
                    function (response)
                    {
                        return response;
                    }
                );
            };
        }

        return {
            link : link,
            templateUrl : 'src/directives/searchbox/searchbox.company.directive.template.html'
        };
    }
);