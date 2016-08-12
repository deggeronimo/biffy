'use strict';

angular.module('biffy.filters').filter(
    'biffyPhone',
    function()
    {
        return function(phone)
        {
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
        }

    }
);
