'use strict';

angular.module('biffyApp')
    .factory('DateRange', function ($location) {
        var urlFormat = 'YYYY-MM-DD';
        var datetimeFormat = 'YYYY-MM-DD HH:mm:ss';
        var humanFormat = 'MMMM D, YYYY';
        var humanWithTimeFormat = 'M/D/YY h:mm A';
        var parseUrl = function (string) {
            return moment(string, urlFormat);
        };
        var parse = function (string) {
            return moment(string, humanFormat).format(urlFormat);
        };
        var service = {
            searchParams: function () {
                if (isDefined($location.search().from)) {
                    return {
                        from: parseUrl($location.search().from),
                        to: parseUrl($location.search().to)
                    }
                }
                return {};
            },
            start: function (dayOfWeek) {
                dayOfWeek = parseInt(dayOfWeek);
                if (dayOfWeek <= moment().day()) {
                    dayOfWeek += 7;
                }
                return {
                    from: moment().day(dayOfWeek - 7),
                    to: moment().day(dayOfWeek - 1)
                };
            },
            startFromUrl: function (params) {
                return {
                    from: parseUrl(params.from),
                    to: parseUrl(params.to)
                }
            },
            startFromTimestamp: function (timestamps) {
                return {
                    from: moment(timestamps.from, 'x'),
                    to: moment(timestamps.to, 'x')
                };
            },
            startFromTimestampUtc: function (timestamps) {
                return {
                    from: moment.utc(timestamps.from, 'x'),
                    to: moment.utc(timestamps.to, 'x')
                };
            },
            nowTimestamp: function () {
                return moment().format('x');
            },
            range: function (start) {
                return {
                    from: start.from.format(humanFormat),
                    to: start.to.format(humanFormat)
                };
            },
            rangeWithTime: function (start) {
                return {
                    from: start.from.format(humanWithTimeFormat),
                    to: start.to.format(humanWithTimeFormat)
                };
            },
            parse: function (string) {
                return parse(string);
            },
            paramObj: function (range) {
                return {from: parse(range.from), to: parse(range.to)};
            },
            momentToDatetime: function (range) {
                return {
                    from: range.from.format(datetimeFormat),
                    to: range.to.format(datetimeFormat)
                }
            },
            pickerOptions: function (start) {
                return {
                    startDate: start.from,
                    endDate: start.to,
                    format: humanFormat
                };
            },
            pickerOptionsWithTime: function (start) {
                return {
                    startDate: start.from,
                    endDate: start.to,
                    format: humanWithTimeFormat,
                    timePicker: true,
                    timePickerIncrement: 1
                }
            }
        };
        return service;
    });