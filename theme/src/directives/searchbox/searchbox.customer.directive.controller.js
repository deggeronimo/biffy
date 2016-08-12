'use strict';

angular.module('biffyApp').directive(
    'customerSearchBox',
    function (RestangularAppService)
    {
        function link(scope)
        {
            scope.phoneFormat = function(phone)
            {
                if (isUndefined(phone))
                {
                    return;
                }

                var format = function(phone)
                {
                    return phone.substring(0, 3) + '-' + phone.substring(3, 6) + '-' + phone.substring(6, 10);
                };

                if (phone.length > 10)
                {
                    return format(phone) + 'x' + phone.substring(11, phone.length);
                }
                else
                {
                    return format(phone);
                }
            };

            scope.getCustomerItems = function (val)
            {
                return RestangularAppService.all('customers?all=1&filter[search]=' + val).getList().then(
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
            templateUrl : 'src/directives/searchbox/searchbox.customer.directive.template.html'
        };
    }
);